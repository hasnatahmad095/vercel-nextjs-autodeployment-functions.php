<?php
add_action ('wp_insert_post', 'auto_build_request', 10, 2);
function auto_build_request ( $post_id, $post) {
    $post_type = get_post_type ( $post_id );
    $allowed_post_types = ['post', 'page'];
    
    if ($post -> post_status == 'publish' ) {
        $data = json_encode ([ 'force_build' => true ]);
        $url =  '?buildCache-false' ;
        $args = array (
            'body' => $data ,
            'headers' => array (
                'Content-Type' => 'application/json'
            ),
        );

        $response = wp_remote_post ( $url , $args );
        if (is_wp_error ($response) ) {
            $body = $response -> get_error_message ();
        } else {
            $body = wp_remote_retrieve_body ($response);
        }
        $content = json_encode ( $post_type );
        $fp = fopen ('error' , 'a');
        fwrite ( $fp , $content . PHP_EOL);
        fclose ( $fp );
    }
}