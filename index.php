<?php

declare(strict_types=1);

use KangBabi\Spreadsheet\Sheet;
use KangBabi\Wrappers\Builder;
use KangBabi\Wrappers\Config;
use KangBabi\Wrappers\Row;

require 'vendor/autoload.php';

function sampleData(): array
{
    return
      [
          [
              'code'         => 'Math-102',
              'course'       => 'Mathematical Analysis 2',
              'credit_units' => '5.0',
              'lec_units'    => '3.0',
              'lab_units'    => '---',
              'class'        => 'BSCS 1-C',
              'faculty'      => 'Lola, Myra Angela',
              'schedules'    => [
                  'Mon 2:30 PM-4:00 PM CS-04-108',
                  'Wed 2:30 PM-4:00 PM CS-04-108',
                  'Fri 3:00 PM-5:00PM CS-04-108',
              ],
          ],
          [
              'code'         => 'Math-102',
              'course'       => 'Mathematical Analysis 2',
              'credit_units' => '5.0',
              'lec_units'    => '3.0',
              'lab_units'    => '---',
              'class'        => 'BSCS 1-C',
              'faculty'      => 'Lola, Myra Angela',
              'schedules'    => [
                  'Tue 9:00 AM-10:30 AM CSSP-MH-Field',
                  'Wed 10:00 AM-12:00 PM CS-04-204',
              ],
          ],
          [
              'code'         => 'Math-102',
              'course'       => 'Mathematical Analysis 2',
              'credit_units' => '5.0',
              'lec_units'    => '3.0',
              'lab_units'    => '---',
              'class'        => 'BSCS 1-C',
              'faculty'      => 'Lola, Myra Angela',
              'schedules'    => [
                  'Thu 4:00 PM-7:00 PM CS-02-104',
              ],
          ],
          [
              'code'         => 'Math-102',
              'course'       => 'Mathematical Analysis 2',
              'credit_units' => '5.0',
              'lec_units'    => '3.0',
              'lab_units'    => '---',
              'class'        => 'BSCS 1-C',
              'faculty'      => 'Lola, Myra Angela',
              'schedules'    => [
                  'Tue 4:00 PM-7:00 PM CS-02-104',
              ],
          ],
          [
              'code'         => 'Math-102',
              'course'       => 'Mathematical Analysis 2',
              'credit_units' => '5.0',
              'lec_units'    => '3.0',
              'lab_units'    => '---',
              'class'        => 'BSCS 1-C',
              'faculty'      => 'Lola, Myra Angela',
              'schedules'    => [
                  'Tue 10:30 AM-12:00 PM CSSP-02-103',
                  'Thu 10:30 AM-12:00 PM CSSP-02-103',
              ],
          ],
      ];
}

/**
 * todo: handle current row injection for sheet methods
 * todo: handle cell styling:
 *  * font
 *  * border
 *  * alignment
 *  * fill
 */

$sheet = new Sheet();
$config = new Config();
$config->orientation('landscape')
    ->pageFit('page', true)
    ->margin('top', 1.5)
    ->paperSize('a4')
    ->columnWidth('A', 20)
    ->repeatRows(1, 5);

$config->apply($sheet->getActiveSheet());

// dd($sheet->getActiveSheet(), $config->getContent());



// die;

$sheet = new Sheet();
$sheet
    ->config(function (Config $config): void {
        $config
            ->orientation('portrait')
            ->pageFit('page')
            ->repeatRows()
            ->paperSize('a4')
            ->columnWidth('A', 13)
            ->columnWidth('B', 23)
            ->columnWidth('C', 12)
            ->columnWidth('D', 10)
            ->columnWidth('E', 10)
            ->columnWidth('F', 9)
            ->columnWidth('G', 36)
            ->columnWidth('H', 17);
    })
    ->header(function (Builder $header): void {
        $header
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'Bicol University');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'Republic of this Philippines');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'Bicol University');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'Legazpi City');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'College of Science');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'UPDATED CERTIFICATE OF REGISTRATION');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'Registration No.: 20181027807');
            })
            ->jump()
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'STUDENT GENERAL INFORMATION');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'B')
                    ->merge('C', 'F')
                    ->merge('G', 'H')
                    ->value('A', 'Student No: 2018-CS-100343')
                    ->value('C', 'College: College of Science')
                    ->value('G', 'School Year: 2018-2019 2nd Semester');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'B')
                    ->merge('C', 'F')
                    ->merge('G', 'H')
                    ->value('A', 'Name: Baldovino, Justine Jañolan')
                    ->value('C', 'Program: Bachelor of Science in Computer Science')
                    ->value('G', 'Curriculum: BSCS2018-2019');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'B')
                    ->merge('C', 'F')
                    ->merge('G', 'H')
                    ->value('A', 'Gender:')
                    ->value('C', 'Major: ---')
                    ->value('G', 'Scholarship: FREE EDUCATION');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'B')
                    ->merge('C', 'F')
                    ->merge('G', 'H')
                    ->value('A', 'Age:')
                    ->value('D', 'Year Level: First Year');
            })
            ->jump()
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'SUBJECT(S)');
            })
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'Regular Subjects');
            })
            ->row(function (Row $row): void {
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

        $schedules = sampleData();

        foreach ($schedules as $schedule) {
            $header->row(function (Row $row) use ($schedule): void {
                $row
                    ->value('A', $schedule['code'])
                    ->value('B', $schedule['course'])
                    ->value('C', (string) $schedule['credit_units'])
                    ->value('D', (string) $schedule['lec_units'])
                    ->value('E', (string) $schedule['lab_units'])
                    ->value('F', $schedule['class'])
                    ->value('G', implode("\n", $schedule['schedules']))
                    ->value('H', $schedule['faculty']);
            });
        }

        $header
            ->row(function (Row $row): void {
                $row
                    ->merge('A', 'H')
                    ->value('A', 'SUB-TOTAL :: Subject(s): 5 Credit Units = 25.0 Lecture Units = 25.0 Laboratory Units = 0.0');
            });
    })
    ->save('COR');
