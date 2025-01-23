<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Question;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        // Kiểm tra xem file có được upload không
        if ($request->hasFile('files')) {
            $file = $request->file('files');

            // Đọc file Excel
            $spreadsheet = IOFactory::load($file->getRealPath());

            // Lấy sheet đầu tiên
            $sheet = $spreadsheet->getActiveSheet();

            // Bỏ qua dòng tiêu đề (nếu có)
            $rows = $sheet->toArray();
            array_shift($rows); // Bỏ qua dòng đầu tiên (tiêu đề)

            // Lặp qua các dòng trong sheet
            foreach ($rows as $row) {
                // Tạo một bản ghi mới trong model Question
                Question::create([
                    'question_text' => $row[0], // Thay 'column1' bằng tên cột trong bảng của bạn
                    'option_1' => $row[1],
                    'option_2' => $row[2], // Thay 'column2' bằng tên cột trong bảng của bạn
                    'option_3' => $row[3],
                    'option_4' => $row[4],
                    'is_correct' => $row[5],
                    'exercise_id' => $request->exercise_id
                ]);
            }

            return response()->json(['success' => 'Import thành công!'], 200);
        }

        return response()->json(['error' => 'Vui lòng chọn file để import.'], 400);
    }

    public function export(Request $request)
    {
        $questions = Question::where('exercise_id', $request->exercise_id)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add header row
        $sheet->setCellValue('A1', 'Question Text');
        $sheet->setCellValue('B1', 'Option 1');
        $sheet->setCellValue('C1', 'Option 2');
        $sheet->setCellValue('D1', 'Option 3');
        $sheet->setCellValue('E1', 'Option 4');
        $sheet->setCellValue('F1', 'Is Correct');
        $sheet->setCellValue('G1', 'Exercise ID');

        // Add data rows
        $rowNumber = 2;
        foreach ($questions as $question) {
            $sheet->setCellValue('A' . $rowNumber, $question->question_text);
            $sheet->setCellValue('B' . $rowNumber, $question->option_1);
            $sheet->setCellValue('C' . $rowNumber, $question->option_2);
            $sheet->setCellValue('D' . $rowNumber, $question->option_3);
            $sheet->setCellValue('E' . $rowNumber, $question->option_4);
            $sheet->setCellValue('F' . $rowNumber, $question->is_correct);
            $sheet->setCellValue('G' . $rowNumber, $question->exercise_id);
            $rowNumber++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = 'questions_export.xlsx';
        $filePath = storage_path('app/exports/' . $fileName);

        $writer->save($filePath);
        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
        

        // if (file_exists($filePath) && is_readable($filePath)) {
        //     return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        // } else {
        //     return response()->json(['error' => 'File không tồn tại hoặc không thể đọc được!'], 404);
        // }
    }
}