<?php

// function flo_acf($acf_function = "get_field", $acf_field_name, $field_prefix = "") {
//   $return = 0;
//   // If has prefix = is clone, if clone get data in a specific way
//   if (Is_String($field_prefix) && $field_prefix != "") {
//     $return = call_user_func($acf_function, $field_prefix)[$acf_field_name];
//   } else {
//     $return = call_user_func($acf_function, $acf_field_name);
//   }
//   return $return;
// }

// function flo_acf_map($fields_array, $acf_function = "get_field", $field_prefix = "") {
//   $data_array = [];
//   foreach ($fields_array as $field_name) {
//     $data_array[$field_name] = flo_acf($acf_function, $field_name, $field_prefix);
//   }
//   return $data_array;
// }

// function flo_get_field($field_name, $field_source = "field", $field_prefix = "") {
//   switch ($field_source) {
//     case "field":
//       get_field($field_prefix.$field_name);
//     break;
//     case "sub_field":
//       get_sub_field($field_prefix.$field_name);
//     break;
//   }
// }

?>
