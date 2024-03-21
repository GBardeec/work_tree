<?php

namespace App\Http\Controllers;

use App\Models\WorkTree;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpWord\IOFactory;


class FileController extends Controller
{
    public function index(): View
    {
        $data = [];

        foreach (WorkTree::all() as $item) {
            $name = $item->name;

            for ($i = 0; $i <= 8; $i++) {
                $filenameField = 'filename_' . $i;

                if (!empty($item->$filenameField)) {
                    $filename = $item->$filenameField;

                    $data[$name][] = [
                        'nameForHref' => str_replace(['local/', '.docx'], '', $filename),
                    ];
                }
            }
        }

        return view('file.index', compact('data'));
    }

    public function show($fileName): string
    {
        $docxFilePath = base_path('local') . DIRECTORY_SEPARATOR . $fileName . '.docx';

        if (!file_exists($docxFilePath)) {
            abort(404, 'Файл не найден');
        }

        $phpWord = IOFactory::load($docxFilePath);

        // Все секции документа
        $sections = $phpWord->getSections();

        $htmlContent = '';

        // Обход каждой секции
        foreach ($sections as $section) {
            foreach ($section->getElements() as $element) {
                // Узнаем тип элемента
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    // Если текст
                    $htmlContent .= '<p>' . $element->getText() . '</p>';
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\Image) {
                    // Если картинка
                    dd(123);
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                    // Если таблица
                    $htmlContent .= '<table>';
                    foreach ($element->getRows() as $row) {
                        $htmlContent .= '<tr>';
                        foreach ($row->getCells() as $cell) {
                            $htmlContent .= '<td>';
                            foreach ($cell->getElements() as $cellElement) {
                                if ($cellElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                    $htmlContent .= $cellElement->getText() . '<br>';
                                }
                            }
                            $htmlContent .= '</td>';
                        }
                        $htmlContent .= '</tr>';
                    }
                    $htmlContent .= '</table>';
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\ListItem) {
                    // Если список
                    $htmlContent .= '<li>' . $element->getText() . '</li>';
                }
            }
        }

        return $htmlContent;
    }


}
