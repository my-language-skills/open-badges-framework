## Explanation of the AJAX 

In this plugin the ajax works with 2 different file, `includes/ajax/custom_ajax.php` and  
`includes/utils/functions_js.php`.

The `function_js.php` make a ajax call to the function of other file, such as the example taken from [this website](https://coderwall.com/p/of7y2q/faster-ajax-for-wordpress).

    jQuery(document).ready(function($){
        var data={
             action:'action_name', //name of the function
             otherData: 'otherValue' //other information that we want to sand to the function
        };
        $.post('http://url/to/your/MY_CUSTOM_AJAX.php', data, function(response){
             alert(response);
        });
    });
    
    
   
For more information:
https://coderwall.com/p/of7y2q/faster-ajax-for-wordpress