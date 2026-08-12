Add-Type -AssemblyName System.Drawing
$bmp = [System.Drawing.Bitmap]::FromFile('src\\assets\\logo.png')
$counts = @{}
for ($y = 0; $y -lt $bmp.Height; $y += 10) {
    for ($x = 0; $x -lt $bmp.Width; $x += 10) {
        $c = $bmp.GetPixel($x, $y)
        if ($c.R -gt 150 -and $c.G -lt 140 -and $c.B -lt 90) {
            $k = '{0:X2}{1:X2}{2:X2}' -f $c.R, $c.G, $c.B
            if ($counts.ContainsKey($k)) { $counts[$k]++ } else { $counts[$k] = 1 }
        }
    }
}
$counts.GetEnumerator() | Sort-Object Value -Descending | Select-Object -First 10 | ForEach-Object { Write-Output $_.Name }
