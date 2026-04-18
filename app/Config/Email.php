<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'noreply@desablanakan.go.id';
    public string $fromName   = 'Desa Blanakan - Sistem Digital';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'Desa Blanakan Email System';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     * For development, use 'smtp' with proper SMTP settings
     * For production, configure according to your server
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     * Configure this according to your email provider
     * Example: 'smtp.gmail.com', 'smtp.mailtrap.io', etc.
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username
     * Set your SMTP username here (Gmail address)
     */
    public string $SMTPUser = 'your-email@gmail.com'; // GANTI DENGAN EMAIL ANDA

    /**
     * SMTP Password
     * Use Gmail App Password (NOT your regular Gmail password)
     * Generate at: https://myaccount.google.com/apppasswords
     */
    public string $SMTPPass = 'your-app-password'; // GANTI DENGAN APP PASSWORD

    /**
     * SMTP Port
     * Common ports: 25, 465 (SSL), 587 (TLS)
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 30;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
