<?php
 /**
     * Allow Restrict Content Pro to see our plugin as part of its extension (doesn't work).
     *
     * @author      @AleRiccardi
     * @since       1.0.0
     *
     * @param $post_types
     *
     * @return array
     */
    public function ag_rcp_metabox_post_types($post_types) {
        $post_types[] = Admin::POST_TYPE_BADGES;
        return $post_types;
    }