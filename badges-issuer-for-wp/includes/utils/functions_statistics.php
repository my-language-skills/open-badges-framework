<?php

/**
 * This is the statistic page
 *
 * @author Nicolas TORION
 * @since 0.6.2
*/

function nb_sended_badges(){
  $result = 0;
  $users = get_users();
  foreach ($users as $user) {
      $badges = get_the_author_meta('user_badges', $user->ID);
      if ($badges){
        $result = $result + count($badges);
      }
  }
  return $result;
}

function sended_badges_by_type(){
  $types = array();
  $users = get_users();
  foreach ($users as $user) {
      $badges = get_the_author_meta('user_badges', $user->ID);
      if ($badges){
        foreach ($badges as $badge) {
          $types[]=$badge['name'];
        }
      }
  }
  sort($types);

  $types_counts = array();
  foreach ($types as $type) {
    $nb_badges_type = 0;
    foreach ($users as $user) {
        $badges = get_the_author_meta('user_badges', $user->ID);
        if ($badges){
          foreach ($badges as $badge) {
            if($badge['name']==$type)
              $nb_badges_type++;
          }
        }
    }
    $types_counts[$type]=$nb_badges_type;
  }
  return $types_counts;
}

function nb_badges_until_date($date) {
  $result = 0;
  $users = get_users();
  $date_object = new DateTime($date);
  foreach ($users as $user) {
      $badges = get_the_author_meta('user_badges', $user->ID);
      if ($badges){
        foreach ($badges as $badge) {
          if($badge['date']!="") {
            $badge_date = new DateTime($badge['date']);
            if($badge_date<$date_object) {
              $result++;
            }
          }
        }
      }
  }
  return $result;
}

function sended_badges_by_dates(){
  $all_weeks = array();
  $date = new DateTime('2017-01-01');
  $current_date = new DateTime(date("Y-m-d"));

  while($date<$current_date) {
    $all_weeks[] = $date->format('Y-m-d');
    $date->modify('+1 week');
  }
  $all_weeks[] = $current_date->format('Y-m-d');

  $dates_counts = array();
  foreach ($all_weeks as $week) {
    $dates_counts[$week] = nb_badges_until_date($week);
  }

  return $dates_counts;
}

function display_pie_chart($types_counts, $target) {
  $values_printed = "[[";
  $nb_elements = count($types_counts);
  $i=0;
  foreach ($types_counts as $type=>$count) {
    $values_printed = $values_printed.'["'.$type.'", '.$count.']';
    if(++$i!=$nb_elements)
      $values_printed = $values_printed.",";
  }
  $values_printed = $values_printed."]]";
  echo "
  <script>
  jQuery(document).ready(function(){
      var plot1 = jQuery.jqplot('".$target."', ".$values_printed.", {
          gridPadding: {top:0, bottom:38, left:0, right:0},
          seriesDefaults:{
              renderer:jQuery.jqplot.PieRenderer,
              trendline:{ show:false },
              rendererOptions: { padding: 8, showDataLabels: true }
          },
          legend:{
              show:true,
              placement: 'outside',
              rendererOptions: {
                  numberRows: 1
              },
              location:'s',
              marginTop: '15px'
          }
      });
  });
</script>
  ";
}

function display_bar_chart($types_counts, $target, $targer_infos) {
  $values = "[";
  $elements = "[";
  $nb_elements = count($types_counts);
  $i=0;
  foreach ($types_counts as $type=>$count) {
    $values = $values.$count;
    $elements = $elements."'".$type."'";
    if(++$i!=$nb_elements) {
      $values = $values.",";
      $elements = $elements.",";
    }
  }
  $values = $values."]";
  $elements = $elements."]";
  echo "
  <script>
  jQuery(document).ready(function(){
        jQuery.jqplot.config.enablePlugins = true;
        var s1 = ".$values.";
        var ticks = ".$elements.";

        plot1 = jQuery.jqplot('".$target."', [s1], {
            // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
            animate: !jQuery.jqplot.use_excanvas,
            seriesDefaults:{
                renderer:jQuery.jqplot.BarRenderer,
                rendererOptions: {
                    varyBarColor: true
                },
                pointLabels: { show: true }
            },
            axes: {
                xaxis: {
                    renderer: jQuery.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            },
            highlighter: { show: false }
        });

        jQuery('#".$target."').bind('jqplotDataClick',
            function (ev, seriesIndex, pointIndex, data) {
                jQuery('#".$target_infos."').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
            }
        );
    });
</script>
  ";
}

function display_plot_chart($dates_counts, $target) {
  $values_printed = "[";
  $nb_elements = count($dates_counts);
  $i=0;
  foreach ($dates_counts as $date=>$count) {
    $values_printed = $values_printed."['".$date."', ".$count."]";
    if(++$i!=$nb_elements)
      $values_printed = $values_printed.",";
  }
  $values_printed = $values_printed."]";
  echo '
  <script>
  jQuery(document).ready(function(){
    // Enable plugins like highlighter and cursor by default.
    // Otherwise, must specify show: true option for those plugins.
    jQuery.jqplot.config.enablePlugins = true;

    var line1='.$values_printed.';

    var plot1 = jQuery.jqplot("'.$target.'", [line1], {
        title:"Badges sended",
        animate: true,
        axes:{
            xaxis:{
                renderer:jQuery.jqplot.DateAxisRenderer,
                rendererOptions:{
                    tickRenderer:jQuery.jqplot.CanvasAxisTickRenderer
                },
                tickOptions:{
                    fontSize:"10pt",
                    fontFamily:"Tahoma",
                    angle:-40
                }
            },
            yaxis:{
                rendererOptions:{
                    tickRenderer:jQuery.jqplot.CanvasAxisTickRenderer},
                    tickOptions:{
                        fontSize:"10pt",
                        fontFamily:"Tahoma",
                        angle:30
                    }
            }
        },
        series:[{ lineWidth:4, markerOptions:{ style:"square" } }],
        cursor:{
            zoom:true,
            looseZoom: true
        }
    });

  });
  </script>
  ';
}
?>
