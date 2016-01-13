<?php

/*
 * Copyright (C) 2016 Libre Informatique <contact at libre-informatique.fr>
 * Copyright (C) 2016 Marcos Bezerra de Menezes <marcos.bezerra at libre-informatique.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Librinfo\BaseEntitiesBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Librinfo\BaseEntitiesBundle\Entity\Traits\Searchable;
use Librinfo\BaseEntitiesBundle\Entity\Repository\SearchableRepository;


/**
 * Batch update of search indexes
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra at libre-informatique.fr>
 * @todo update all search indexes for a namespace or for a bundle
 *          it could be based on GenerateEntitiesDoctrineCommand, but it is not yet PSR-4 compatible
 *          see : https://github.com/doctrine/DoctrineBundle/issues/282
 */
class UpdateSearchCommand extends DoctrineCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('librinfo:update:search')
            ->setDescription('Batch update of search indexes')
            ->addArgument('name', InputArgument::REQUIRED, 'Entity FQDN class name or Doctrine alias')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command updates the search indexes for a single entity :

  <info>php %command.full_name% MyCustomBundle:User</info>
  <info>php %command.full_name% MyCustomBundle/Entity/User</info>

EOT
        );
    }


    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = strtr($input->getArgument('name'), '/', '\\');

        if (false !== $pos = strpos($name, ':')) {
            $name = $this->getContainer()->get('doctrine')->getAliasNamespace(substr($name, 0, $pos)).'\\'.substr($name, $pos + 1);
        }

        if (class_exists($name)) {
            $output->writeln(sprintf('Updating search index for entity "<info>%s</info>"', $name));
        } else {
            throw new \RuntimeException(sprintf('%s class doesn\'t exist.', $name));
        }

        // Check if the entity has the Searchable trait
        $reflector = new \ReflectionClass($name);
        $traits = $reflector->getTraitNames();
        if ( ! in_array(Searchable::class, $traits) ) {
            throw new \RuntimeException(sprintf('%s class doesn\'t have the Searchable trait.', $reflector->getName()));
        }

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $metadata = $em->getClassMetadata($name);
        $repo = new SearchableRepository($em, $metadata);
        $repo->batchUpdate();
        $output->writeln('DONE');
    }


}
