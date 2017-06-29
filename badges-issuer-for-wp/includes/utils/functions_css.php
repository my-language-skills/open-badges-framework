<?php
// CSS STYLES FUNCTIONS

/**
 * Applies css styles of some elements.
 *
 * @author Nicolas TORION
 * @since 1.0.0
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
    border-width: 1px;
    border-radius: 10px;
    border-style: solid;
    font-size: 20px;
    position: absolute;
    top:0;
  }

  .success {
    background-color: #A7DFA9;
    border-color: #2F7D31;
    color: #2F7D31;
  }

  .error {
    background-color: #F66C7A;
    border-color: #D80D21;
    color: #D80D21;
  }
  </style>
  <?php
}
 ?>
