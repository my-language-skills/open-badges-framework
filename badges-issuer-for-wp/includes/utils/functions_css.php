<?php
// CSS STYLES FUNCTIONS

/**
 * Applies css styles of some elements.
 *
 * @author Nicolas TORION
 * @since 0.3
*/
function apply_css_styles() {
  ?>

  <style>

  .input-hidden {
    position: absolute;
    left: -9999px
  }

  input[type=radio]:checked + label>img {
    border: 1px solid #fff;
    box-shadow: 0 0 0px 4px red;
  }

  input[type=radio] + label>img {
    border: 1px solid transparent;
    width: 70px;
    height: 70px;
    transition: 500ms all;
    border-radius: 50%;
    margin: 5px;
  }

  .message {
    padding: 10px;
    font-size: 20px;
    position: absolute;
    top:0;
    color: #FFF;
  }

  .success {
    background-color: #01BF2B;
  }

  .error {
    background-color: #C90101;
  }

  </style>
  <?php
}
 ?>
