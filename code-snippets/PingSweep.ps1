#Requires -version 4.0
#Requires -module NetTCPIP

#https://www.petri.com/building-ping-sweep-tool-powershell
$LocalIpSubnet = (Get-NetIPAddress -AddressFamily IPv4).Where({ $_.InterfaceAlias -match "WiFi" }).ipAddress -replace "\.\d{1,3}$", "."

$subnet = "10.10.10."
$start = 1
$end = 254

$start..$end | foreach {
    if (Test-Connection -computername "$subnet$_" -Quiet -count 1) {
        Try {
            $hostname = (Resolve-DNSName -Name $subnet$_ -ErrorAction Stop).Namehost
        }
        Catch {
            #Write-Verbose "Failed to resolve host name for $subnet$_"
            #set a value
            $hostname = "unknown"
        }
        $status = "UP"
        $color = "Cyan"
    } else {
        $status = "DOWN"
        $color = "Red"
        $hostname = ""
    }
    Write-Host ("$subnet$_", $status, $hostname) -ForegroundColor $color
}
