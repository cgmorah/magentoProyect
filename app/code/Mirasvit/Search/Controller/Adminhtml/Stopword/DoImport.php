<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-search
 * @version   1.0.146
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\Search\Controller\Adminhtml\Stopword;

use Mirasvit\Search\Controller\Adminhtml\Stopword;

class DoImport extends Stopword
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $generator = $this->stopwordService->import($data['dictionary'], $data['store_id']);

            $result = [
                'stopwords'         => 0,
                'errors'            => 0,
                'error_message'     => '',
            ];

            foreach ($generator as $result) {
            }

            if ($result['stopwords'] > 0) {
                $this->messageManager->addSuccessMessage(__('Imported %1 stopword(s).', $result['stopwords']));
            }

            if ($result['errors']) {
                if (empty($result['error_message'])) {
                    $this->messageManager->addWarningMessage(__('%1 errors.', $result['errors']));
                } else {
                    $this->messageManager->addWarningMessage(__('%1', $result['error_message']));
                }
            }

        } else {
            $this->messageManager->addErrorMessage('No data to import.');
        }

        return $resultRedirect->setPath('*/*/');
    }
}
