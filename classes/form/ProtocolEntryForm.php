<?php

namespace HeimrichHannot\Privacy\Form;

use HeimrichHannot\FormHybrid\Form;
use HeimrichHannot\Privacy\Manager\ProtocolManager;
use HeimrichHannot\Privacy\Model\ProtocolArchiveModel;
use HeimrichHannot\Privacy\Util\ProtocolUtil;

class ProtocolEntryForm extends Form
{
    protected $noEntity = true;
    protected $strMethod = 'POST';

    public function compile() {}

    protected function afterActivationCallback(\DataContainer $dc, $objModel, $submissionData = null)
    {
        parent::afterActivationCallback($dc, $objModel, $submissionData);

        $this->updateReferenceEntity($submissionData);
        $this->deleteReferenceEntity($submissionData);

        if (isset($GLOBALS['TL_HOOKS']['privacy_afterActivation']) && \is_array($GLOBALS['TL_HOOKS']['privacy_afterActivation']))
        {
            foreach ($GLOBALS['TL_HOOKS']['privacy_afterActivation'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($submissionData, $this->objModule);
                }
                elseif (\is_callable($callback))
                {
                    $callback($submissionData, $this->objModule);
                }
            }
        }
    }

    protected function afterSubmitCallback(\DataContainer $dc)
    {
        if (!$this->objModule->formHybridAddOptIn)
        {
            $this->updateReferenceEntity();
            $this->deleteReferenceEntity();
        }
    }

    protected function updateReferenceEntity($submissionData = null)
    {
        if (!$this->objModule->privacyAddReferenceEntity || !$this->objModule->privacyUpdateReferenceEntityFields)
        {
            return;
        }

        $protocolManager = new ProtocolManager();
        $protocolManager->updateReferenceEntity(
            $this->objModule->formHybridPrivacyProtocolArchive,
            $submissionData,
            deserialize($this->objModule->formHybridEditable, true),
            $this->objModule
        );
    }

    protected function deleteReferenceEntity($submissionData = null)
    {
        if (!$this->objModule->privacyAddReferenceEntity || !$this->objModule->privacyDeleteReferenceEntityAfterOptAction)
        {
            return;
        }

        $protocolManager = new ProtocolManager();

        $affectedRows = $protocolManager->deleteReferenceEntity(
            $this->objModule->formHybridPrivacyProtocolArchive,
            $submissionData
        );

        if ($affectedRows !== false && $affectedRows > 0 && $this->objModule->addOptOutDeletePrivacyProtocolEntry) {
            if ($this->objModule->optOutDeletePrivacyProtocolDescription) {
                $data['description'] = $this->objModule->optOutDeletePrivacyProtocolDescription;
            }

            $protocolManager->addEntryFromModule(
                $this->objModule->optOutDeletePrivacyProtocolEntryType,
                $this->objModule->optOutDeletePrivacyProtocolArchive,
                $data,
                $this->objModule
            );
        }
    }
}