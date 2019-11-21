<?php

class Vmw_Wc_New_Product extends Vmw_Wc_Sync
{
    public function storeProduct()
    {
        $this->setData();
        $this->init();
        try {
            $res = $this->makeRequest();
        } catch (\GuzzleHttp\Exception\GuzzleException $exception) {
            wp_die($exception->getMessage());
        }

        $res = json_decode($res->getBody()->getContents(), false);
        $res = $res->data;

        static::setPostSku($this->post_id, $res->sku);
        static::setPostId($this->post_id, $res->id);
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
