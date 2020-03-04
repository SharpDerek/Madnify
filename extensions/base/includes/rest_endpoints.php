<?php

// example of extending the wp-rest api with a custom GET endpoint
// for consistent naming of api enxtension, please use api slug of /madnify/v1
// try this route by accessing /wp-json/madnify/v1/test?param_1=p1&param_2=p2

// you should see a response like this:
// {
//   "status": "give it a rest!",
//   "param1": "p1",
//   "param2": "p2",
//   "all_params": {
//     "param_1": "p1",
//     "param_2": "p2"
//   }
// }

function madnify_rest_test( WP_REST_Request $request ) {
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
}

function activate_sample_rest_endpoint(){
  add_action( 'rest_api_init', function () {
    register_rest_route( 'madnify/v1', '/test', array(
      'methods' => 'GET',
      'callback' => 'madnify_rest_test'
      ) );
    } );
}


// uncomment this to activate this rest endpoint
// activate_sample_rest_endpoint();