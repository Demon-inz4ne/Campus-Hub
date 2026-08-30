<?php
include_once __DIR__ . "/PHPMailer/PHPMailer.php";
include_once __DIR__ . "/PHPMailer/SMTP.php";
include_once __DIR__ . "/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CampusMailer {
    public static function sendVerificationEmail($recipient_email, $student_name, $campus_name, $student_number, $vcode) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'iamjanithgeeshan@gmail.com';
            $mail->Password   = 'vdsjflfugsuseufr';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('iamjanithgeeshan@gmail.com', 'CampusHub Student Verification');
            $mail->addReplyTo('iamjanithgeeshan@gmail.com', 'CampusHub Support');
            $mail->addAddress($recipient_email, $student_name);

            $mail->isHTML(true);
            $mail->Subject = 'CampusHub Verification Code - ' . $campus_name;

            // HTML Email Template
            $bodyContent = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background-color: #f8f9fa;">
                <div style="background-color: #0d6efd; color: #ffffff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                    <h2 style="margin: 0;">🎓 CampusHub Student Verification</h2>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Cross-Campus Academic & Activity Portal</p>
                </div>
                <div style="padding: 25px; background-color: #ffffff; border-radius: 0 0 8px 8px;">
                    <p style="font-size: 16px; color: #333333;">Dear <strong>' . htmlspecialchars($student_name) . '</strong>,</p>
                    <p style="font-size: 14px; color: #555555; line-height: 1.6;">
                        Thank you for registering on <strong>CampusHub</strong> as a student under <strong>' . htmlspecialchars($campus_name) . '</strong>.
                    </p>
                    <div style="background-color: #f1f5f9; padding: 15px; border-left: 4px solid #0d6efd; margin: 20px 0; border-radius: 4px;">
                        <p style="margin: 0 0 8px 0; font-size: 13px; color: #666;"><strong>Student Details:</strong></p>
                        <p style="margin: 0 0 4px 0; font-size: 14px; color: #333;">• <strong>Student ID:</strong> ' . htmlspecialchars($student_number) . '</p>
                        <p style="margin: 0; font-size: 14px; color: #333;">• <strong>Campus:</strong> ' . htmlspecialchars($campus_name) . '</p>
                    </div>
                    <p style="font-size: 14px; color: #333; text-align: center; margin-top: 25px;"><strong>Your Email Verification Code:</strong></p>
                    <div style="text-align: center; margin: 15px 0;">
                        <span style="font-size: 28px; font-weight: bold; letter-spacing: 3px; color: #0d6efd; background-color: #e7f1ff; padding: 10px 25px; border-radius: 8px; border: 2px dashed #0d6efd; display: inline-block;">
                            ' . htmlspecialchars($vcode) . '
                        </span>
                    </div>
                    <p style="font-size: 13px; color: #666666; line-height: 1.5; text-align: center; margin-top: 20px;">
                        Your account registration is currently pending approval by your campus Sub-Admin. Once approved, you can sign in to access your student feed and campus events.
                    </p>
                    <hr style="border: none; border-top: 1px solid #eeeeee; margin: 25px 0;">
                    <p style="font-size: 12px; color: #888888; text-align: center; margin: 0;">
                        If you did not request this registration, please ignore this email.<br>
                        &copy; 2026 CampusHub Platform. All rights reserved.
                    </p>
                </div>
            </div>';

            $mail->Body = $bodyContent;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    public static function sendPasswordResetEmail($recipient_email, $user_name, $vcode) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'iamjanithgeeshan@gmail.com';
            $mail->Password   = 'vdsjflfugsuseufr';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('iamjanithgeeshan@gmail.com', 'CampusHub Security');
            $mail->addReplyTo('iamjanithgeeshan@gmail.com', 'CampusHub Support');
            $mail->addAddress($recipient_email, $user_name);

            $mail->isHTML(true);
            $mail->Subject = 'CampusHub - Password Reset Verification Code';

            // HTML Email Template
            $bodyContent = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background-color: #f8f9fa;">
                <div style="background-color: #dc3545; color: #ffffff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                    <h2 style="margin: 0;">🔒 CampusHub Password Reset</h2>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Account Security & Verification</p>
                </div>
                <div style="padding: 25px; background-color: #ffffff; border-radius: 0 0 8px 8px;">
                    <p style="font-size: 16px; color: #333333;">Hello <strong>' . htmlspecialchars($user_name) . '</strong>,</p>
                    <p style="font-size: 14px; color: #555555; line-height: 1.6;">
                        We received a request to reset your password for your <strong>CampusHub</strong> account. Use the verification code below to reset your password.
                    </p>
                    <p style="font-size: 14px; color: #333; text-align: center; margin-top: 25px;"><strong>Your Password Reset Code:</strong></p>
                    <div style="text-align: center; margin: 15px 0;">
                        <span style="font-size: 28px; font-weight: bold; letter-spacing: 3px; color: #dc3545; background-color: #fde8e8; padding: 10px 25px; border-radius: 8px; border: 2px dashed #dc3545; display: inline-block;">
                            ' . htmlspecialchars($vcode) . '
                        </span>
                    </div>
                    <p style="font-size: 13px; color: #666666; line-height: 1.5; text-align: center; margin-top: 20px;">
                        This code is required to set up a new password for your account. If you did not request a password reset, please secure your account or ignore this message.
                    </p>
                    <hr style="border: none; border-top: 1px solid #eeeeee; margin: 25px 0;">
                    <p style="font-size: 12px; color: #888888; text-align: center; margin: 0;">
                        &copy; 2026 CampusHub Platform. All rights reserved.
                    </p>
                </div>
            </div>';

            $mail->Body = $bodyContent;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>
