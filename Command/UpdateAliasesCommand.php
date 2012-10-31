<?php

namespace Cedriclombardot\OgonePaymentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAliasQuery;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAliasPeer;

use Cedriclombardot\OgonePaymentBundle\Feedback\OgoneCodes;

class UpdateAliasesCommand extends ContainerAwareCommand
{
     /**
     * @see Command
     */
    protected function configure()
    {
        $this
        ->setDefinition(array(
        ))
        ->setDescription('Update alias status')
        ->setHelp(<<<EOT
Find ogone files and update statuses
EOT
        )
        ->setName('ogone:update-aliases')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $aliases =  OgoneAliasQuery::create()
                        ->filterByStatus(OgoneAliasPeer::STATUS_PENDING)
                        ->find();

        $output->writeln('<info>'.count($aliases).'</info> aliases in <info>pending</info> status');

        foreach ($aliases as $alias) {
            $output->writeln('Get file for alias <info>'.$alias->getUuid().'</info> ');

            $file = $this->getContainer()->get('ogone.file_downloader')->getFile($alias->getFileId());

            $xml = new \SimpleXMLElement($file->getContent());
            $status = $xml->xpath('PAYMENT/@STATUS');
            $status = (string) $status[0]['STATUS'];

            $statusLib = $xml->xpath('PAYMENT/@LIB');
            $statusLib = (string) $statusLib[0]['LIB'];

            $output->writeln('>> Ogone status <info>'.$status.'</info> '.$statusLib);

            if (OgoneCodes::isPayed($status)) {
                $alias->setStatus(OgoneAliasPeer::STATUS_OK);
                $output->writeln('>> Set Alias STATUS <info>OK</info> ');
            } elseif (OgoneCodes::isRefused($status)) {
                $alias->setStatus(OgoneAliasPeer::STATUS_KO);
                $output->writeln('>> Set Alias STATUS <info>KO</info> ');
            }

            $alias->save();
        }
    }
}
