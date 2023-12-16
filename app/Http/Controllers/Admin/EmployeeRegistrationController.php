<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeBankModel;
use App\Models\EmployeEducationModel;
use App\Models\EmployeesModel;
use App\Models\EmployeModel;
use App\Models\EmployeServiceModel;
use App\MyMethod\AdminMethod;
use App\MyMethod\EmailSender;
use App\MyMethod\EmployeMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeRegistrationController extends Controller
{

    // Registration API OLD
    public function register(Request $request)
    {
        // Employe Registration 
        // $employe = EmployeModel::create([
        //     'emp_code' => $request->emp_code,
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone' => $request->phone,
        //     'district_code' => $request->district_code,
        //     'block_code' => $request->block_code,
        //     'gp_code' => $request->gp_code,
        //     'level_id' => $request->level_id,
        // ]);
        $status = 400;
        $message = [];
        $error_message = [
            "name.required" => 'Name Is Required ',
            "email.required" => 'Email ID Required ',
            "phone.required" => 'Phone Number Is R
            equired ',
            "state_code.required" => 'State Code Is Required ',
            "integer" => "Code Should Be Numeric",
            "email" => "Enter A Valid Email",
            "min" => "Phone Number Must Be 10 Digits",
            "max" => "Phone Number Must Be 10 Digits"
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|min:10|max:10',
                'state_code' => 'required'
            ],
            $error_message
        );
        if ($validator->fails()) {
            array_push($message, $validator->errors()->all());
        } else {
            $check_employe_data = EmployeMethod::checkEmployeData('employe', 'email', $request->email);
            if ($check_employe_data) {
                $check_employe_data = EmployeMethod::checkEmployeData('employe', 'phone', $request->phone);
                if ($check_employe_data) {
                    $level_code = null;
                    if ($request->state_code) {
                        if ($request->district_code) {
                            if ($request->block_code) {
                                if ($request->gp_code) {
                                    $level_code = "GP";
                                } else {
                                    $level_code = "BL";
                                }
                            } else {
                                $level_code = "DT";
                            }
                        } else {
                            $level_code = "ST";
                        }
                    }
                    if ($level_code) {
                        $password = EmployeMethod::generatePassword();
                        $emp_code = EmployeMethod::generateEmpCode($level_code);
                        $check = false;
                        try {
                            // $employe_id = DB::table('employe')
                            //     ->insertGetId([
                            //         'emp_code' => $emp_code . (DB::select('select id from employe') == null ? 0 : DB::select('select id from employe order by id desc limit 1')[0]->id),
                            //         'name' => $request->name,
                            //         'email' => $request->email,
                            //         'password' => Hash::make($password),
                            //         'phone' => $request->phone,
                            //         'district_code' => $request->district_code,
                            //         'block_code' => $request->block_code,
                            //         'gp_code' => $request->gp_code,
                            //         'level_id' => $request->level_id,
                            //     ]);
                            $employe_save = EmployeModel::create([
                                'emp_code' => $emp_code . (DB::select('select id from employe') == null ? 0 : DB::select('select id from employe order by id desc limit 1')[0]->id),
                                'name' => $request->name,
                                'email' => $request->email,
                                'password' => Hash::make($password),
                                'phone' => $request->phone,
                                'district_code' => $request->district_code,
                                'block_code' => $request->block_code,
                                'gp_code' => $request->gp_code,
                                'level_id' => $request->level_id,
                            ]);
                            $check = true;
                        } catch (Exception $err) {
                            $check = false;
                        }
                        if ($check) {
                            // array_push($message, ['Done']);
                            $employe_data = [
                                'name' => $employe_save->name,
                                'emp_code' => $employe_save->emp_code,
                                'email' => $employe_save->email,
                                'password' => $password,
                                'subject' => 'Employe Registration'
                            ];
                            $check_email_send = EmailSender::emailSend($employe_data, $employe_save->email, 'employe_register');
                            if ($check_email_send) {
                                $status = 200;
                                array_push($message, [$employe_save, 'Registration Completed ']);
                            } else {
                                array_push($message, ['Registration Completed But Email Not Send !']);
                            }
                        } else {
                            array_push($message, ['Try Again Registration Not Completed']);
                        }
                    } else {
                        array_push($message, ['Select Your Level']);
                    }
                } else {
                    array_push($message, ['Phone Already Registered !']);
                }
            } else {
                $done = ["ok"];
                array_push($message, ['Email Already Registered !']);
            }
        }
        // $token = $employe->createToken('EmployeToken')->accessToken;
        return response()->json(['status' => $status, 'message' => $message], 200);
    }

    // Registration API NEW
    public function registration(Request $request)
    {
        $status = 400;
        $message = [];
        $error_message = [
            "required" => ':attribute Is Required Field',w
            'email' => ':attribute Only accepts Email Type',
            'max' => 'File Size Only 3mb',
            'regex' => 'Enter A Valid :attribute ',
            'image' => ':attribute Is Only File Type'
        ];
        $validator = Validator::make(
            $request->all(),
            [
                // Personal Validation 

                // "employe_name" => "required",
                // "employe_designation" => "required",
                // "service_status" => "required|integer",
                // "employe_phone" => "required|regex:/^\d{10}$/",
                // "employe_category" => "required",
                // "employe_alt_number" => "required|regex:/^\d{10}$/",
                // "employe_email" => "required|email",
                "employe_profile" => "required|image|max:3000",
                // "employe_father_name" => "required",
                // "employe_mother_name" => "required",
                // "employe_dob" => "required|date",
                "employe_birth_certificate" => "required|max:3000|mimes:pdf",
                // "pan_number" => "required",
                // "aadhar_number" => "required",
                // "gender" => "required",
                // "nationality" => "required",
                // "personal_marks_of_identification" => "required",
                // "caste" => "required",
                // "race" => "required",
                "pwd_document" => "required|max:3000|mimes:pdf",
                // "posted_district" => "required|integer",
                // "posted_block" => "required|integer",
                // "posted_gp" => "required|integer",
                // "date_of_order" => "required|date",
                "order_document" => "required|max:3000|mimes:pdf",
                // "date_of_joining" => "required|date",
                "current_joining_document" => "required|max:3000|mimes:pdf",
                // "branch" => "required",
                // "initial_date_of_joining" => "required|date",
                "initial_appointment_letter" => "required|max:3000|mimes:pdf",
                "initial_joining_letter" => "required|max:3000|mimes:pdf",
                // "state" => "required|integer",
                // "district" => "required|integer",
                // "block" => "required|integer",
                // "gp" => "required|integer",
                // "address" => "required",

                // // Service Record Validation If Any

                // "promoted_to_curr_des" => "",
                // "promoted_from_curr_des" => "",
                // "bdo_status" => "integer",
                // "transferred_from_district" => "integer",
                // "transferred_from_block" => "integer",
                // "transferred_from_gp" => "integer",
                // "transferred_to_district" => "integer",
                // "transferred_to_block" => "integer",
                // "transferred_to_gp" => "integer",
                // "transferred_document" => "image:3000",
                // "transferred_date" => 'date',
                // "previous_joining_document" => 'image:3000',
                // "previous_joining_date" => "date",
                // "service_branch" => '',

                // // Education Details 

                // "schoolName" => "required",
                // "boardName" => "required",
                // "marks" => "required|integer",
                // "percentageCGPA" => "required",
                // "passingYear" => "required",
                // // Intermediate (Class XII)
                // "interSchoolCollegeName" => "required",
                // "interBoardName" => "required",
                // "interMarks" => "required|integer",
                // "interPercentageCGPA" => "required",
                // "interPassingYear" => "required",
                // // Graduate
                // "graduateSchoolCollegeName" => "required",
                // "gaduateUniversityName" => "required",
                // "graduateMarks" => "required|integer",
                // "graduatePercentageCGPA" => "required",
                // "graduatePassingYear" => "required",
                // // Post Graduate
                // "postSchoolCollegeName" => "required",
                // "postUniversityName" => "required",
                // "postMarks" => "required|integer",
                // "postPercentageCGPA" => "required",
                // "postPassingYear" => "required",

                // // Bank Details 

                // "account_number" => "required",
                // "account_name" => "required",
                // "ifsc_code" => "required",
                // "bank_name" => "required",
                // "branch_name" => "required"
            ],
            $error_message
        );
        if ($validator->fails()) {
            array_push($message, $validator->errors()->all());
        } else {
            $check_service_records = true;
            if ($request->isServiceRecord == "true") {
                $service_validator = Validator::make(
                    $request->all(),
                    [
                        // "promoted_to_curr_des" => "required",
                        // "promoted_from_curr_des" => "required",
                        // "bdo_status" => "required|integer",
                        // "transferred_from_district" => "required|integer",
                        // "transferred_from_block" => "required|integer",
                        // "transferred_from_gp" => "required|integer",
                        // "transferred_to_district" => "required|integer",
                        // "transferred_to_block" => "required|integer",
                        // "transferred_to_gp" => "required|integer",
                        "transferred_document" => "required|max:3000|mimes:pdf",
                        // "transferred_date" => 'required|date',
                        "previous_joining_document" => 'required|max:3000|mimes:pdf',
                        // "previous_joining_date" => "required|date",
                        // "service_branch" => 'required|',
                    ],
                    $error_message
                );
                if ($service_validator->fails()) {
                    array_push($message, $service_validator->errors()->all());
                    $check_service_records = false;
                }
            }
            if ($check_service_records) {
                // array_push($message, [$request->all()]);

                // Image Upload 
                // $current_joining_document = $request->file('current_joining_document');
                // $temp_bank_statement_url = $current_joining_document->store('public/images/1');
                // $check_image = Storage::exists('public/images/1/cF7ctMFhV4XrutlFJ02zgIGXQUfYhQEIjAdphzDK.pdf');
                // Storage::delete('public/images/1/cF7ctMFhV4XrutlFJ02zgIGXQUfYhQEIjAdphzDK.pdf');
                $check_employe_data = EmployeMethod::checkEmployeData('employees', 'employe_email', $request->employe_email);
                if ($check_employe_data) {
                    $check_employe_data = EmployeMethod::checkEmployeData('employees', 'employe_phone', $request->employe_phone);
                    if ($check_employe_data) {
                        $level_code = "ST";
                        // if ($request->state) {
                        //     if ($request->district) {
                        //         if ($request->block) {
                        //             if ($request->gp) {
                        //                 $level_code = "GP";
                        //             } else {
                        //                 $level_code = "BL";
                        //             }
                        //         } else {
                        //             $level_code = "DT";
                        //         }
                        //     } else {
                        //         $level_code = "ST";
                        //     }
                        // }
                        if ($level_code) {
                            $password = EmployeMethod::generatePassword();
                            $emp_code = EmployeMethod::generateEmpCode($level_code);

                            $check = true;
                            try {
                                $save_employe = EmployeesModel::create([
                                    'employe_code' => $emp_code . (DB::select('select id from employees') == null ? 0 : DB::select('select id from employees order by id desc limit 1')[0]->id),
                                    "password" => Hash::make('$password'),
                                    "employe_name" => 'employe_name',
                                    "employe_designation" => 'employe_designation',
                                    "service_status" => '1',
                                    "employe_phone" => '7002142549',
                                    "employe_category" => 'employe_category',
                                    "employe_alt_number" => 'employe_alt_number',
                                    "employe_email" => 'mirdephawa222@gmail.com',
                                    "employe_profile" => 'employe_profile',
                                    "employe_father_name" => 'employe_father_name',
                                    "employe_mother_name" => 'employe_mother_name',
                                    "employe_dob" => '01-02-23',
                                    "employe_birth_certificate" => 'employe_birth_certificate',
                                    "pan_number" => 'pan_number',
                                    "aadhar_number" => 'aadhar_number',
                                    "gender" => 'gender',
                                    "nationality" => 'nationality',
                                    "personal_marks_of_identification" => 'personal_marks_of_identification',
                                    "caste" => 'caste',
                                    "race" => 'race',
                                    "pwd_document" => 'pwd_document',
                                    "posted_district" => '123',
                                    "posted_block" => '1234',
                                    "posted_gp" => '1234',
                                    "date_of_order" => '02-12-23',
                                    "order_document" => 'order_document',
                                    "date_of_joining" => '02-12-23',
                                    "joining_document" => 'joining_document',
                                    "current_joining_document" => 'current_joining_document',
                                    "branch" => 'branch',
                                    "initial_date_of_joining" => '02-12-23',
                                    "initial_appointment_letter" => 'initial_appointment_letter',
                                    "initial_joining_letter" => 'initial_joining_letter',
                                    "state" => '999',
                                    "district" => '123',
                                    "block" => '1234',
                                    "gp" => '123',
                                    "address" => 'address',
                                    // "schoolName" => $request->schoolName,
                                    // "boardName" => $request->boardName,
                                    // "marks" => $request->marks,
                                    // "percentageCGPA" => $request->percentageCGPA,
                                    // "passingYear" => $request->passingYear,
                                    // "interSchoolCollegeName" => $request->interSchoolCollegeName,
                                    // "interBoardName" => $request->interBoardName,
                                    // "interMarks" => $request->interMarks,
                                    // "interPercentageCGPA" => $request->interPercentageCGPA,
                                    // "interPassingYear" => $request->interPassingYear,
                                    // "graduateSchoolCollegeName" => $request->graduateSchoolCollegeName,
                                    // "gaduateUniversityName" => $request->gaduateUniversityName,
                                    // "graduateMarks" => $request->graduateMarks,
                                    // "graduatePercentageCGPA" => $request->graduatePercentageCGPA,
                                    // "graduatePassingYear" => $request->graduatePassingYear,
                                    // "postSchoolCollegeName" => $request->postSchoolCollegeName,
                                    // "postUniversityName" => $request->postUniversityName,
                                    // "postMarks" => $request->postMarks,
                                    // "postPercentageCGPA" => $request->postPercentageCGPA,
                                    // "postPassingYear" => $request->postPassingYear,
                                    // "account_number" => $request->account_number,
                                    // "account_name" => $request->account_name,
                                    // "ifsc_code" => $request->ifsc_code,
                                    // "bank_name" => $request->bank_name,
                                    // "branch_name" => $request->branch_name
                                ]);
                                $check = true;
                            } catch (Exception $err) {
                                $check = false;
                            }
                            if ($check) {
                                $employe_files = [
                                    'employe_profile' => $request->file('employe_profile'),
                                    'employe_birth_certificate' => $request->file('employe_birth_certificate'),
                                    'pwd_document' => $request->file('pwd_document'),
                                    'order_document' => $request->file('order_document'),
                                    'current_joining_document' => $request->file('current_joining_document'),
                                    'initial_appointment_letter' => $request->file('initial_appointment_letter'),
                                    'initial_joining_letter' => $request->file('initial_joining_letter')
                                ];
                                $check_files = EmployeMethod::uploadEmployeFiles($employe_files, $save_employe->id);
                                $check = $check_files[0];
                                $employe_files = $check_files[1];
                                if ($check) {
                                    if (EmployeMethod::uploadFileDatabase('employees', $employe_files, $save_employe->id)) {
                                        $check_second_step = true;
                                        try {
                                            $save_bank = EmployeBankModel::create([
                                                'employe_id' => $save_employe->id,
                                                'account_number' => 'account_number',
                                                'account_name' => 'account_name',
                                                'ifsc_code' => 'ifsc_code',
                                                'bank_name' => 'bank_name',
                                                'branch_name' => 'branch_name',
                                            ]);
                                            $check_second_step = true;
                                        } catch (Exception $err) {
                                            $check_second_step = false;
                                        }
                                        if ($check_second_step) {
                                            $check_thrid_step = true;
                                            $main_education_details = [
                                                'matric' => [
                                                    'employe_id' => $save_employe->id,
                                                    'employe_degree' => 'matric 1',
                                                    'board_name' => 'matric baord 1',
                                                    'marks' => '123',
                                                    'percentage' => '123',
                                                    'passing_year' => 'matric passing year'
                                                ],
                                                'hs' => [
                                                    'employe_id' => $save_employe->id,
                                                    'employe_degree' => 'hs 1',
                                                    'board_name' => 'hs baord 1',
                                                    'marks' => '123',
                                                    'percentage' => '123',
                                                    'passing_year' => 'hs passing year'
                                                ],
                                                'isGraduate' => [
                                                    'employe_id' => $save_employe->id,
                                                    'employe_degree' => 'graduation 1',
                                                    'board_name' => 'graduation baord 1',
                                                    'marks' => '123',
                                                    'percentage' => '123',
                                                    'passing_year' => 'graduation passing year'
                                                ],
                                                'isPostGraduate' => [
                                                    'employe_id' => $save_employe->id,
                                                    'employe_degree' => 'post_graduation 1',
                                                    'board_name' => 'post_graduation baord 1',
                                                    'marks' => '123',
                                                    'percentage' => '122',
                                                    'passing_year' => 'post_graduation passing year'
                                                ],
                                            ];
                                            $count_education_error = 0;
                                            $check_education = [
                                                $request->isGraduate,
                                                $request->isPostGraduate
                                            ];
                                            foreach ($main_education_details as $main_education_key) {
                                                try {
                                                    $check_is_value = true;
                                                    if ($count_education_error == 2 || $count_education_error == 3) {
                                                        if ($check_education[$count_education_error - 2] == "false") {
                                                            $check_is_value = false;
                                                        }
                                                    }
                                                    if ($check_is_value) {
                                                        $save_education = EmployeEducationModel::create(
                                                            $main_education_key
                                                        );
                                                    }
                                                    $check_thrid_step = true;
                                                    $count_education_error++;
                                                } catch (Exception $err) {
                                                    $check_thrid_step = false;
                                                    break;
                                                }
                                            }
                                            if ($check_thrid_step) {
                                                $check_fourth_step = true;
                                                if ($request->isServiceRecord == "true") {
                                                    try {
                                                        $save_service = EmployeServiceModel::create([
                                                            'employe_id' => $save_employe->id,
                                                            'promoted_to_curr_des' => 'promted',
                                                            'promoted_from_curr_des' => 'promted',
                                                            'bdo_status' => 'promted',
                                                            'transferred_from_district' => '123',
                                                            'transferred_from_block' => '1234',
                                                            'transferred_from_gp' => '12345',
                                                            'transferred_to_district' => '123',
                                                            'transferred_to_block' => '1234',
                                                            'transferred_to_gp' => '12345',
                                                            'transferred_document' => 'promted',
                                                            'transferred_date' => '01-02-23',
                                                            'previous_joining_document' => 'promted',
                                                            'previous_joining_date' => '01-02-23',
                                                            'service_branch' => 'promted',
                                                        ]);
                                                        $check_fourth_step = true;
                                                    } catch (Exception $err) {
                                                        $check_fourth_step = false;
                                                    }
                                                }
                                                $employe_service_files = [
                                                    'transferred_document' => $request->file('transferred_document'),
                                                    'previous_joining_document' => $request->file('previous_joining_document')
                                                ];
                                                if ($check_fourth_step) {
                                                    if ($request->isServiceRecord == "true") {
                                                        $check_service_files = EmployeMethod::uploadEmployeFiles($employe_service_files, $save_employe->id);
                                                        $check = $check_service_files[0];
                                                        $employe_service_files = $check_service_files[1];
                                                        if ($check) {
                                                            if (EmployeMethod::uploadFileDatabase_2('employe_service_record', $employe_service_files, 'employe_id', $save_employe->id)) {
                                                                $check_fourth_step == true;
                                                            } else {
                                                                $check_fourth_step = false;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($check_fourth_step) {
                                                    $email_data = [
                                                        'name' => $save_employe->employe_name,
                                                        'emp_code' => $save_employe->employe_code,
                                                        'email' => $save_employe->employe_email,
                                                        'password' => $password,
                                                        'subject' => 'Employe Registration '
                                                    ];
                                                    $check_email_send = EmailSender::emailSend($email_data, $save_employe->employe_email, 'employe_register');
                                                    if ($check_email_send) {
                                                        $status = 200;
                                                        array_push($message, ['Registration Completed !']);
                                                    } else {
                                                        array_push($message, ['Registration Completed But Email Not Send !']);
                                                    }
                                                } else {
                                                    if (EmployeMethod::revertEmployeData('employe_service_record', 'employe_id', $save_employe->id)) {
                                                        if (EmployeMethod::revertEmployeData('employe_education_details', 'employe_id', $save_employe->id)) {
                                                            if (EmployeMethod::revertEmployeData('employe_bank_details', 'employe_id', $save_employe->id)) {
                                                                if (EmployeMethod::revertEmployeData('employees', 'id', $save_employe->id)) {
                                                                    EmployeMethod::revertEmployeFile($employe_files, $save_employe->id);
                                                                    EmployeMethod::revertEmployeFile($employe_service_files, $save_employe->id);
                                                                    array_push($message, ['Error Throw While Add Service Records Data']);
                                                                } else {
                                                                    array_push($message, ['Server Error Please Ask Developers']);
                                                                }
                                                            } else {
                                                                array_push($message, ['Server Error Please Ask Developers']);
                                                            }
                                                        } else {
                                                            array_push($message, ['Server Error Please Ask Developers']);
                                                        }
                                                    } else {
                                                        array_push($message, ['Server Error Please Ask Developers']);
                                                    }
                                                }
                                            } else {
                                                $check_revert_Thrid_step = true;
                                                if ($count_education_error != 0) {
                                                    foreach ($main_education_details as $main_education_key => $main_education_value) {
                                                        if (EmployeMethod::revertEmployeData('employe_education_details', 'employe_id', $save_employe->id)) {
                                                            $check_revert_Thrid_step = true;
                                                        } else {
                                                            $check_revert_Thrid_step = false;
                                                            break;
                                                        }
                                                    }
                                                }
                                                if ($check_revert_Thrid_step) {
                                                    if (EmployeMethod::revertEmployeData('employe_bank_details', 'employe_id', $save_employe->id)) {
                                                        if (EmployeMethod::revertEmployeData('employees', 'id', $save_employe->id)) {
                                                            EmployeMethod::revertEmployeFile($employe_files, $save_employe->id);
                                                            array_push($message, ['Error Throw While Add Education Data']);
                                                        } else {
                                                            array_push($message, ['Server Error Please Ask Developer ']);
                                                        }
                                                    } else {
                                                        array_push($message, ['Server Error Please Ask Developer ']);
                                                    }
                                                } else {
                                                    array_push($message, ['Server Error Please Ask Developer ']);
                                                }
                                            }
                                        } else {
                                            if (EmployeMethod::revertEmployeData('employees', 'id', $save_employe->id)) {
                                                EmployeMethod::revertEmployeFile($employe_files, $save_employe->id);
                                                array_push($message, ['Error Throw While Add Bank Data']);
                                            } else {
                                                array_push($message, ['Server Error Please Ask Developer']);
                                            }
                                        }
                                    } else {
                                        if (EmployeMethod::revertEmployeData('employees', 'id', $save_employe->id)) {
                                            EmployeMethod::revertEmployeFile($employe_files, $save_employe->id);
                                            array_push($message, ['Error Throw While Upload Personal Files In Database']);
                                        } else {
                                            array_push($message, ['Server Error Please Ask Developers']);
                                        }
                                    }
                                } else {
                                    if (EmployeMethod::revertEmployeData('employees', 'id', $save_employe->id)) {
                                        array_push($message, ['Error Throw While Upload Files']);
                                    } else {
                                        array_push($message, ['Serer Error Please Ask Developer']);
                                    }
                                }
                            } else {
                                array_push($message, ["Personal Database Error Try Again !"]);
                            }
                        } else {
                            array_push($message, ['Select Your Level']);
                        }
                    } else {
                        array_push($message, ['Phone Already Registered !']);
                    }
                } else {
                    array_push($message, ['Email Already Registered !']);
                }
            }
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
    public function getDistricts(Request $request)
    {
        $status = 400;
        $message = "";
        if (AdminMethod::getAllDistricts()) {
            $districts = AdminMethod::getAllDistricts();
            $status = 200;
            return response()->json(['status' => $status, 'districts' => $districts], 200);
        } else {
            $message = "Server Error Try Later !";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    public function getBlocks(Request $request)
    {
        $status = 400;
        $message = "";
        $district_code = $request->district_code;
        if ($district_code) {
            $blocks = AdminMethod::getAllBlocks($district_code);
            if ($blocks) {
                if (count($blocks) == 0) {
                    $message = "No Blocks Found ";
                } else {
                    $status = 200;
                    return response()->json(['status' => $status, 'blocks' => $blocks], 200);
                }
            } else {
                $message = "Server Error Try Later !";
            }
        } else {
            $message = "Didn't Recieve District Code ";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
    public function getGPs(Request $request)
    {
        $status = 400;
        $message = "";
        $block_code = $request->block_code;
        if ($block_code) {
            $gps = AdminMethod::getAllGPs($block_code);
            if ($gps) {
                if (count($gps) == 0) {
                    $message = "No GP found!";
                } else {
                    $status = 200;
                    return response()->json(['status' => $status, 'gps' => $gps], 200);
                }
            } else {
                $message = "Server Error Try Later !";
            }
        } else {
            $message = "Didn't Recieve Block Code ";
        }
        return response()->json(['status' => $status, 'message' => $message], 200);
    }
}
