
(function( $ ) {
    // 'use strict';

      // $( window ).load(function() {
      $(function() {


      $(document).on( 'click', '.nav-tab-wrapper.bitwise-tabs a', function() {
            var tab = $(this).data('tab-id');

            $('.nav-tab').removeClass('nav-tab-active');
            $('.content').removeClass('content-tab-active');
            
            if(tab=="retention-score"){
                retentionDatatable();
            }else{
                sesionDatatable();
            }

            $('#'+tab).addClass('nav-tab-active');
            $('#content-'+tab).addClass('content-tab-active');
            return false;
        });


        function retentionDatatable() {

             $('#retention-list').DataTable({
                "aLengthMenu": [[15, 30, 45, -1], [15, 30, 45, "All"]],
                "iDisplayLength": 30,
                "processing": true,
                "serverSide":true,
                  "destroy": true,

                "ajax":{
                    "url":php_vars.ajaxurl+'?action=retention_score_ajax_request',
                    "type": "POST"
                },
                "columns": [

                    {"data": "user_id"},
                    {"data": "course_id"},
                    {"data": "retention_score"},
                    {"data": "retention_category"},
                    {"data": "status"},
                    {"data": "created_on"}

                ]
           }); 
             // $('#quiz-request-list').clear();
        }
        
        function sesionDatatable() {        
            $('#sesion-list').DataTable({
                "processing": true,
                "aLengthMenu": [[15, 30, 45, -1], [15, 30, 45, "All"]],
                "iDisplayLength": 30,
                "serverSide": true,
                 "destroy" : true,
                    "ajax": {
                        "url": php_vars.ajaxurl+'?action=sesion_report_ajax_request',
                        "type": "POST"
                    },
                    "columns": [
                           { "data": "user_id" },
                           { "data": "course_id" },
                           { "data": "post_id" },
                           { "data": "time_spent" },
                           { "data": "cron_status" },
                           { "data": "cron_updated_on" },
                           { "data": "created_on" }
                       ]

                       

            }); //Callback list.
            // $('#callback-list').clear();
        }


    });

})( jQuery );