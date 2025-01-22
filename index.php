<?php

declare(strict_types = 1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Config;
use KangBabi\Wrappers\Header;
use KangBabi\Wrappers\Row;

require 'vendor/autoload.php';

/**
 * todo: handle current row injection for sheet methods
 * todo:
 */
$sheet = new Sheet();
$sheet
  ->config(function (Config $config) {
      $config
        ->orientation('portrait')
        ->orientation('landscape')

        ->pageFit('page')
        ->pageFit('height', true)
        ->pageFit('width', true)

        ->margin('top', 20)
        ->margin('bottom', 20)
        ->margin('left', 20)
        ->margin('right', 20)

        ->repeatRows()

        ->paperSize('a4')

        ->columnWidth('A', 30)
        ->columnWidth('B', 30)
        ->columnWidth('C', 30)
        ->columnWidth('D', 30)
        ->columnWidth('E', 30)
        ->columnWidth('F', 30)
        ->columnWidth('G', 30)
        ->columnWidth('H', 30);
  })
  ->header(function (Header $header) {
      $header
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'Bicol University');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'Republic of this Philippines');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'Bicol University');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'Legazpi City');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'College of Science');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'UPDATED CERTIFICATE OF REGISTRATION');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'Registration No.: 20181027807');
        })
        ->jump()
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'STUDENT GENERAL INFORMATION');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'B')
              ->merge('D', 'E')
              ->merge('G', 'H')
              ->value('A', 'Student No: 2018-CS-100343')
              ->value('D', 'College: College of Science')
              ->value('G', 'School Year: 2018-2019 2nd Semester');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'B')
              ->merge('D', 'E')
              ->merge('G', 'H')
              ->value('A', 'Name: Baldovino, Justine Jañolan')
              ->value('D', 'Program: Bachelor of Science in Computer Science')
              ->value('G', 'Curriculum: BSCS2018-2019');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'B')
              ->merge('D', 'E')
              ->merge('G', 'H')
              ->value('A', 'Gender:')
              ->value('D', 'Major: ---')
              ->value('G', 'Scholarship: FREE EDUCATION');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'B')
              ->merge('D', 'E')
              ->merge('G', 'H')
              ->value('A', 'Age:')
              ->value('D', 'Year Level: First Year');
        })
        ->jump()
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'SUBJECT(S)');
        })
        ->row(function (Row $row) {
            $row
              ->merge('A', 'H')
              ->value('A', 'Regular Subjects');
        })
        ->row(function (Row $row) {
            $row
              ->value('A', 'Course Code')
              ->value('B', 'Course Title')
              ->value('C', 'Credit Units')
              ->value('D', 'Lec Units')
              ->value('E', 'Lab Units')
              ->value('F', 'Class')
              ->value('G', 'Schedule')
              ->value('H', 'Faculty');
        });
  })
  ->save('COR');
