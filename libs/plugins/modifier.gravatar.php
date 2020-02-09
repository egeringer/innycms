<?php

function smarty_modifier_gravatar($string,$size) {
	return "https://www.gravatar.com/avatar/".md5($string)."?d=mm&s=$size";
}

