# PHP Ransomware

PHP ransomware that encrypts your files as well as file and directory names.

Ransomware is set to start encrypting files and directories from the server's web root directory and only inside the server's web root directory.

**Ransomware will self-destruct upon running, which means you only have one chance at decrypting your data.**

**Keep also in mind that each decryption file has a uniquely generated salt used in encryption and as such cannot be replaced with another decryption file.**

Tested on XAMPP for Windows v7.4.3 (64-bit) with PHP v7.4.3.

P.S. On web servers other than XAMPP (Apache) you might need to load OpenSSL and Multibyte String libraries within PHP.

Made for educational purposes. I hope it will help!

## How to Run

**Care not to do any damage! Backup your server files before running ransomware!**

Copy ['\\src\\encrypt.php'](https://github.com/ivan-sincek/php-ransomware/blob/master/src/encrypt.php) to your server's web root directory (e.g. to '\\xampp\\htdocs\\' on XAMPP).

Navigate to the encryption file with your preferred web browser.

Decryption file will be created automaticly after the encryption phase.

## Images

<p align="center"><img src="https://github.com/ivan-sincek/php-ransomware/blob/master/img/ransomware.jpg" alt="Ransomware"></p>

<p align="center">Figure 1 - Ransomware</p>

<p align="center"><img src="https://github.com/ivan-sincek/php-ransomware/blob/master/img/encrypted_content.jpg" alt="Encrypted Content"></p>

<p align="center">Figure 2 - Encrypted Content</p>
