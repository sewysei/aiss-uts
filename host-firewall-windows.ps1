# Jalankan sebagai Administrator di PowerShell (klik kanan PowerShell -> "Run as Administrator")
# Layer Host & Network - versi native Windows (pengganti ufw/iptables untuk defender yang jalan murni di Windows)

Write-Host "Mengatur Windows Defender Firewall untuk Portal Akademik..." -ForegroundColor Cyan

# Blokir semua inbound secara default (Windows Firewall sudah default deny untuk profil Public/Private,
# baris ini memastikan behaviour-nya eksplisit dan konsisten)
Set-NetFirewallProfile -Profile Domain,Public,Private -DefaultInboundAction Block -DefaultOutboundAction Allow

# Hanya izinkan port yang benar-benar dipakai project ini
New-NetFirewallRule -DisplayName "Portal Akademik - HTTPS (443)" -Direction Inbound -Protocol TCP -LocalPort 443 -Action Allow
New-NetFirewallRule -DisplayName "Portal Akademik - HTTP redirect (80)" -Direction Inbound -Protocol TCP -LocalPort 80 -Action Allow

# Kalau perlu SSH ke host Windows (OpenSSH Server), ganti port default dulu di sshd_config lalu:
# New-NetFirewallRule -DisplayName "SSH custom port" -Direction Inbound -Protocol TCP -LocalPort 2222 -Action Allow

# Tampilkan rule yang aktif untuk didokumentasikan di laporan (screenshot output ini)
Get-NetFirewallRule -DisplayName "Portal Akademik*" | Format-Table DisplayName, Enabled, Direction, Action

Write-Host "`nCatatan untuk BAB III (kurangi attack surface):" -ForegroundColor Yellow
Write-Host "- Nonaktifkan service Windows yang tidak dipakai lewat: Get-Service | Where-Object {`$_.Status -eq 'Running'}"
Write-Host "- Matikan SMB/File Sharing kalau tidak dipakai: Set-SmbServerConfiguration -EnableSMB1Protocol `$false"
Write-Host "- Docker Desktop sendiri membuka port internal (WSL2 vEthernet) - itu normal dan tidak perlu dibuka di firewall publik"
