<?php
// This file is part of the Magic Square Quiz Maker (magicsq) application.
// Copyright (C) 2014-2016 Ken Lowrie
//
// magicsq is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// magicsq is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// See LICENSE.TXT for more information.   

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startHeader("mainHeader");
$fmt->h1("Magic Squares Help");
$fmt->endHeader();

$fmt->startSection("helpPage");
$fmt->startDivClass("helpInfo");

$fmt->addLink("mkms.php","Click Me to Return to the Magic Square Maker Page");
$fmt->brk(2);

include ('msdocs.html');

$fmt->brk();
$fmt->addLink("mkms.php","Click Me to Return to the Magic Square Maker Page");

$fmt->endDiv();	// close div.helpInfo

$fmt->endSection();

include('footer1.inc');


?>