<?php

class Vmw_Wc_Existing_Product extends Vmw_Wc_Sync
{
    public function updateProduct()
    {
        $this->setData();
        $this->init();

        try {
            $this->makeUpdateRequest();
        } catch (\GuzzleHttp\Exception\GuzzleException $exception) {
            wp_die($exception->getMessage());
        }
    }

    private function init(): void
    {
        if (count($this->data) === 0) {
            wp_die(__('Incorrect data passed to Vindmijnwijn.nl, disable product sync to continue.', 'vmw-wc'));
        }

        if (!static::hasToken()) {
            wp_die(__('No Vindmijnwijn.nl Vendor token supplied. Aborting.', 'vmw-wc'));
        }

        if (!static::hasBaseUrl()) {
            wp_die(__('No URL passed to base Vindmijnwijn.nl URL. Aborting.', 'vmw-wc'));
        }
    }
}
