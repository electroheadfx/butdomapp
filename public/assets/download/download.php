<?php

header("Content-disposition: attachment; filename=reglement.pdf");
header("Content-type: application/pdf");
readfile("reglement.pdf");