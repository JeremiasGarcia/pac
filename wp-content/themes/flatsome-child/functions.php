<?php
add_filter('the_content', 'dcms_add_custom_content');
 
function dcms_add_custom_content($content){
    $jwt = login();
    if ( ! is_page('test') ) return $content;
    $html = get_data_api_buscarUsuario($jwt);
 
    return $content.$html;
}
 
function get_data_api_buscarUsuario($jwt){
    $body = [
        'usuario' => "USER-57384",
        'par_sistema' => "PORTAL_PAC"
    ];
     
    $body = wp_json_encode( $body );
 
    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
            'x-access-token' => $jwt
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];
 
    $url = 'http://devetg.i2tsa.com.ar:3008/api/proc/pac_buscar_usuario_crm';
    $response = wp_remote_post( $url, $options);
 
    $bodyR = wp_remote_retrieve_body($response);
 
    $data = json_decode($bodyR);
    // echo print_r($data);
    // echo print_r($data->dataset);
   
    $meta = [
        'id_users_sql' => $data->dataset[0]->id_users_sql,
        'id_sql'=> $data->dataset[0]->id_sql,
        'permiso_sql'=> $data->dataset[0]->permiso_sql,
        'numero_agente_c_sql' => $data->dataset[0]->numero_agente_c_sql,
        'numero_subagente_c_sql' => $data->dataset[0]->numero_subagente_c_sql,
        'age_red_id_c_sql' => $data->dataset[0]->age_red_id_c_sql,
        'razon_social_sql' => $data->dataset[0]->razon_social_sql
    ];
 
    add_usermeta($meta);
 
}
 
function login(){
    $body = [
        'usuario'  => 'admin',
        'pass' => '1q2w',
    ];
     
    $body = wp_json_encode( $body );
 
    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];
 
    $url = 'http://devetg.i2tsa.com.ar:3008/login/';
    $response = wp_remote_post( $url, $options);
    $bodyR = wp_remote_retrieve_body($response);
    $data = json_decode($bodyR);
 
    // file_put_contents("log_test_login.php", "imprimo respuesta del servidor: ".$response."\n", FILE_APPEND);
    return $data->dataset[0]->jwt;
 
}
 
function add_usermeta( $meta ) {
    foreach ($meta as $metaKey => $metaValue) {
        echo 'Clave: ' . $metaKey . '////// Valor: ' . $metaValue . '<br>';
        $current_user = wp_get_current_user();
        $userId = $current_user->data->ID;
        $value = get_usermeta($userId, $meta_key = $metaKey);
        if ($metaValue != $value) {
            add_user_meta( $userId, $metaKey, $metaValue);
        }
    }
}
 
function get_data_api_cu($jwt){
    $body = [
        'usuario' => "USER-57384",
        'par_sistema' => "PORTAL_PAC"
    ];
     
    $body = wp_json_encode( $body );
 
    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
            'x-access-token' => $jwt
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];
 
    $url = 'http://devetg.i2tsa.com.ar:3008/api/proc/pac_buscar_usuario_crm';
    $response = wp_remote_post( $url, $options);
 
    $bodyR = wp_remote_retrieve_body($response);
 
    $data = json_decode($bodyR);
    // echo print_r($data);
    // echo print_r($data->dataset);
 
    $template = '<table class="table-data">
                <tr>
                    <th>id_usuario_suite</th>
                    <th>id_pac</th>
                    <th>num_permiso</th>
                    <th>num_agencia</th>
                    <th>num_subagencia</th>
                    <th>id_agente</th>
                    <th>razon_social</th>
                </tr>
                {data}
            </table>';
   
    if ( $data ){
            $str = '';
            foreach ($data->dataset as $user) {
                    $str .= "<tr>";
                    $str .= "<td>{$user->id_users_sql}</td>";
                    $str .= "<td>{$user->id_sql}</td>";
                    $str .= "<td>{$user->permiso_sql}</td>";
                    $str .= "<td>{$user->numero_agente_c_sql}</td>";
                    $str .= "<td>{$user->numero_subagente_c_sql}</td>";
                    $str .= "<td>{$user->age_red_id_c_sql}</td>";
                    $str .= "<td>{$user->razon_social_sql}</td>";
                    $str .= "</tr>";
                }
            }
           
    $html = str_replace('{data}', $str, $template);
           
    return $html;
}
 
function get_data_api(){
    $url = 'http://devetg.i2tsa.com.ar:3008/api/VerProductos?name=lk[QLA%]&id=not[14]&id=not[20220117]';
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'x-access-token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjdzMmpjYTlzMHY3amdsOGg4MnZqYTF1OXY1IiwiaWF0IjoxNjUyMjc0OTc2LCJleHAiOjE2NTIyOTI5NzZ9.IAsopH3SVXi-Zpdv48daaikChlLSCL_I2vriDXa-85E'
    ));
 
    $response = wp_remote_get($url,$args);
    // $response = wp_remote_request($url, $args);
 
    if (is_wp_error($response)) {
        error_log("Error: ". $response->get_error_message());
        return false;
    }
 
    $body = wp_remote_retrieve_body($response);
    // echo print_r($body);
 
    $data = json_decode($body);
    // echo print_r($data->dataset);
 
    $template = '<table class="table-data">
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                    </tr>
                    {data}
                </table>';
 
    if ( $data ){
        $str = '';
        foreach ($data->dataset as $qla) {
            $str .= "<tr>";
            $str .= "<td>{$qla->id}</td>";
            $str .= "<td>{$qla->name}</td>";
            $str .= "</tr>";
        }
    }
 
    $html = str_replace('{data}', $str, $template);
 
    return $html;
}
