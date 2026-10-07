# Jalankan di PowerShell biasa dari dalam folder nginx/ - tidak perlu WSL2 atau Git Bash
# Memakai container openssl sekali-pakai supaya tidak perlu install openssl di Windows

New-Item -ItemType Directory -Force -Path .\certs | Out-Null

docker run --rm -v "${PWD}\certs:/certs" alpine/openssl req -x509 -nodes -days 365 -newkey rsa:2048 `
  -keyout /certs/portal.key `
  -out /certs/portal.crt `
  -subj "/C=ID/ST=JawaTimur/L=Sidoarjo/O=PortalAkademik/CN=portal-akademik.local"

Write-Host "Sertifikat dibuat di nginx\certs\ (self-signed, untuk demo lokal saja)" -ForegroundColor Green
