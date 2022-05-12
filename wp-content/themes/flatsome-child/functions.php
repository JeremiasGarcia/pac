<?php

add_filter('the_content', 'dcms_add_custom_content');

function dcms_add_custom_content($content){

	if ( ! is_page('test') ) return $content;

	$html = get_data_api_cu();
	return $content.$html;
}

function get_data_api_cu(){
	$body = [
        'usuario' => "USER-57384",
    	'par_sistema' => "PORTAL_PAC"
    ];
     
    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json',
			'x-access-token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6Imw5OGU0bjNoYnZwaDdtMmQ0MWlpYmlpYmQ2IiwiaWF0IjoxNjUyMzg1NDU0LCJleHAiOjE2NTI0MDM0NTR9.XyxZy50L_zKl0e9qrntbhG5UBP37_b9iiDb2w3IZZ0M'
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
	$varLocalPHP = 'testLocal';
	?>
	<script>
		var key = '<?php echo $varLocalPHP; ?>';
		console.log('prueba de script');
		console.log('prueba de variable: ',key);
		// window.localStorage.setItem(key, 'prueba local storage php-js');
		window.localStorage.setItem('test', "<?php echo $varLocalPHP; ?>");

	</script>
	<?php
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