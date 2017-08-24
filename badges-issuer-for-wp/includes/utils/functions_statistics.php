<?php
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

function display_pie_chart($types_counts) {
  $values_printed = "[[";
  $nb_elements = count($types_counts);
  $i=0;
  foreach ($types_counts as $type=>$count) {
    $values_printed = $values_printed."['".$type."', ".$count."]";
    if(++$i!=$nb_elements)
      $values_printed = $values_printed.",";
  }
  $values_printed = $values_printed."]]";
  echo "
  <script>
  jQuery(document).ready(function(){
      var plot1 = jQuery.jqplot('pie1', ".$values_printed.", {
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
?>
