<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
 * All Rights Reserved.
 *
 * This file is part of ZenCMS.
 * ZenCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License.
 *
 * ZenCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with ZenCMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ZenCMS
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

class mailer
{

    const STRIP_RETURN_PATH = TRUE;

    private $to = NULL;
    private $subject = NULL;
    private $textMessage = NULL;
    private $headers = NULL;
    private $recipients = NULL;
    private $cc = NULL;
    private $cco = NULL;
    private $from = NULL;
    private $replyTo = NULL;
    private $attachments = array();
    private $address;

    public function __construct($to = NULL, $subject = NULL, $textMessage = NULL, $headers = NULL)
    {
        $this->to = $to;
        $this->recipients = $to;
        $this->subject = $subject;
        $this->textMessage = $textMessage;
        $this->headers = $headers;
        return $this;
    }

    public function send()
    {
        if (is_null($this->to)) {
            throw new Exception("Must have at least one recipient.");
        }

        if (is_null($this->from)) {
            throw new Exception("Must have one, and only one sender set.");
        }

        if (is_null($this->subject)) {
            throw new Exception("Subject is empty.");
        }

        if (is_null($this->textMessage)) {
            throw new Exception("Message is empty.");
        }

        $this->packHeaders();
        ini_set("SMTP", "smtp.localhost.com");
        ini_set('sendmail_from', $this->address);
        $sent = @mail($this->to, $this->subject, $this->textMessage, $this->headers);
        if (!$sent) {
            return false;
        } else {
            return true;
        }
    }

    public function addRecipient($name, $address)
    {
        $this->recipients .= (is_null($this->recipients)) ? ("$name <$address>") : (", " . "$name <$address>");
        $this->to .= (is_null($this->to)) ? $address : (", " . $address);
        return $this;
    }

    public function addCC($name, $address)
    {
        $this->cc .= (is_null($this->cc)) ? ("$name <$address>") : (", " . "$name <$address>");
        return $this;
    }

    public function addCCO($name, $address)
    {
        $this->cc .= (is_null($this->cc)) ? ("$name <$address>") : (", " . "$name <$address>");
        return $this;
    }

    public function setFrom($name, $address)
    {
        $this->from = "$name <$address>" . PHP_EOL;
        $this->address = $address;
        if (is_null($this->replyTo)) {
            $this->replyTo = $address . PHP_EOL;
        }
        return $this;
    }

    public function setReplyTo($address)
    {
        $this->replyTo = $address . PHP_EOL;
        return $this;
    }

    public function fillSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function fillMessage($textMessage)
    {
        $this->textMessage = $textMessage;
        return $this;
    }

    public function attachFile($filePath)
    {
        $this->attachments[] = $filePath;
        return $this;
    }

    private function packHeaders()
    {
        if (!isset($this->headers)) {
            $this->headers = "MIME-Version: 1.0" . PHP_EOL;
            $this->headers .= "To: " . $this->recipients . PHP_EOL;
            $this->headers .= "From: " . $this->from . PHP_EOL;

            if (self::STRIP_RETURN_PATH !== TRUE) {
                $this->headers .= "Reply-To: " . $this->replyTo . PHP_EOL;
                $this->headers .= "Return-Path: " . $this->from . PHP_EOL;
            }

            if ($this->cc) {
                $this->headers .= "Cc: " . $this->cc . PHP_EOL;
            }

            if ($this->cco) {
                $this->headers .= "Bcc: " . $this->cco . PHP_EOL;
            }

            $str = "";

            if ($this->attachments) {
                $random_hash = md5(date('r', time()));
                $headers .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-" . $random_hash . "\"" . PHP_EOL;

                $pos = strpos($this->textMessage, "<html>");
                if ($pos === false) {
                    $str .= "--PHP-mixed-$random_hash" . PHP_EOL;
                    $str .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
                    $str .= "Content-Transfer-Encoding: 7bit" . PHP_EOL;
                    $str .= $this->textMessage . PHP_EOL;
                }

                if ($pos == 0) {
                    $str .= "--PHP-mixed-$random_hash" . PHP_EOL;
                    $str .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
                    $str .= "Content-Transfer-Encoding: 7bit" . PHP_EOL;
                    $str .= $this->textMessage . PHP_EOL;
                }

                if ($pos > 0) {
                    $str .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-" . $random_hash . "\"" . PHP_EOL;
                    $str .= "--PHP-alt-$random_hash" . PHP_EOL;
                    $str .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
                    $str .= "Content-Transfer-Encoding: 7bit";
                    $str .= substr($this->textMessage, 0, $pos);
                    $str .= PHP_EOL;
                    $str .= "--PHP-alt-$random_hash" . PHP_EOL;
                    $str .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
                    $str .= "Content-Transfer-Encoding: 7bit";
                    $str .= substr($this->textMessage, $pos);
                    $str .= "--PHP-alt-$random_hash--" . PHP_EOL;
                }

                foreach ($this->attachments as $key => $value) {
                    $mime_type = mime_content_type($value);
                    //$mime_type = "image/jpeg";
                    $attachment = chunk_split(base64_encode(file_get_contents($value)));
                    $fileName = basename("$value");
                    $str .= "--PHP-mixed-$random_hash" . PHP_EOL;
                    $str .= "Content-Type: $mime_type; name=\"$fileName\"" . PHP_EOL;
                    $str .= "Content-Disposition: attachment" . PHP_EOL;
                    $str .= "Content-Transfer-Encoding: base64" . PHP_EOL;
                    $str .= PHP_EOL;
                    $str .= "$attachment";
                    $str .= PHP_EOL;
                }
                $str .= "--PHP-mixed-$random_hash--" . PHP_EOL;
            } else {
                $pos = strpos($this->textMessage, "<html>");

                $headers = "";

                if ($pos === false) {
                    $headers .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
                    $headers .= "Content-Transfer-Encoding: 7bit";
                    $str .= $this->textMessage . PHP_EOL;
                }

                if ($pos === 0) {
                    $headers .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
                    $headers .= "Content-Transfer-Encoding: 7bit";
                    $str .= $this->textMessage . PHP_EOL;
                }

                if ($pos > 0) {
                    $random_hash = md5(date('r', time()));
                    $headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-" . $random_hash . "\"" . PHP_EOL;
                    $str .= "--PHP-alt-$random_hash" . PHP_EOL;
                    $str .= "Content-Type: text/plain; charset=\"utf-8\"" . PHP_EOL;
                    $str .= "Content-Transfer-Encoding: 7bit";
                    $str .= substr($this->textMessage, 0, $pos);
                    $str .= PHP_EOL;
                    $str .= "--PHP-alt-$random_hash" . PHP_EOL;
                    $str .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
                    $str .= "Content-Transfer-Encoding: 7bit";
                    $str .= substr($this->textMessage, $pos);
                    $str .= "--PHP-alt-$random_hash--" . PHP_EOL;
                }
            }
            $this->textMessage = $str;
        }
    }

}

/* usage */
/*
  $myMessage = "<html>...";
  try {
  // minimal requirements to be set
  $dummy = new mailer();
  $dummy->setFrom("My Website", "contact@mywebsite.com");
  $dummy->addRecipient("Holly","holly@email.com");
  $dummy->fillSubject("About stuff");
  $dummy->fillMessage($myMessage);

  // options below are completely optional
  $dummy->addRecipient("Marcus", "marcus@anothermail.org");
  $dummy->addCC("Mr. Carlson", "manager@business.com");
  $dummy->addCCO("Mr. X", "mistery@mindmail.com");
  $dummy->attachFile('../files/file1.txt');

  // now we send it!
  $dummy->send();
  } catch (Exception $e) {
  echo $e->getMessage();
  exit(0);
  }

  echo "Success!";
 */