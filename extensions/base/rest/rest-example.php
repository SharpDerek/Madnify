<?php

$rest_example = function (WP_REST_Request $request) {
  // You can access parameters via direct array access on the object:
  $param = $request['param_1'];

  // Or via the helper method:
  $param2 = $request->get_param( 'param_2' );

  // You can get the combined, merged set of parameters:
  $parameters = $request->get_params();

  // The individual sets of parameters are also available, if needed:
  $url_parameters = $request->get_url_params();
  $query_parameters = $request->get_query_params();
  $body_parameters = $request->get_body_params();
  $json_parameters = $request->get_json_params();
  $default_parameters = $request->get_default_params();

  // Uploads aren't merged in, but can be accessed separately:
  $file_parameters = $request->get_file_params();

 return array(
   'status' => 'give it a rest!',
   'param1' => $param,
   'param2' => $param2,
   'all_params' => $parameters
  );
};


$this->register_rest_route(array(
	'namespace' => 'madnify/'.$this->get_slug(), // Namespace for this endpoint. Used in conjunction with 'route'
	'route' => '/rest-example',  // Route for this endpoint. Used in conjuction with 'namespace'
	'args' => array(
    // list all methods as associative arrays, like so:
		array(
			'methods' => 'GET',
      'callback' => $rest_example
    ),
		array(
			'methods' => 'POST',
      'callback' => $rest_example
    )
	)
));