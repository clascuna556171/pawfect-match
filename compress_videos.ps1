$ErrorActionPreference = "Stop"

$url = "https://github.com/BtbN/FFmpeg-Builds/releases/download/latest/ffmpeg-master-latest-win64-gpl.zip"
$zipFile = "C:\Users\Sebaz\pet-adoption\ffmpeg.zip"
$extractPath = "C:\Users\Sebaz\pet-adoption\ffmpeg_temp"

Write-Host "Downloading ffmpeg from GitHub using curl..."
curl.exe -L -o $zipFile $url
Write-Host "Extracting ffmpeg..."
if (Test-Path $extractPath) { Remove-Item -Recurse -Force $extractPath }
Expand-Archive -Path $zipFile -DestinationPath $extractPath -Force

$ffmpegExe = Get-ChildItem -Path $extractPath -Filter "ffmpeg.exe" -Recurse | Select-Object -First 1 | Select-Object -ExpandProperty FullName
Write-Host "Found ffmpeg at: $ffmpegExe"

$videosPath = "C:\Users\Sebaz\pet-adoption\public\videos\pets"
$videos = Get-ChildItem -Path $videosPath -Filter "*.mp4"

foreach ($video in $videos) {
    $inputFile = $video.FullName
    $outputFile = $inputFile.Replace(".mp4", "_compressed.mp4")
    
    $originalSize = [math]::Round($video.Length / 1MB, 2)
    Write-Host "Compressing $($video.Name) (Original Size: ${originalSize}MB)..."
    
    # Run ffmpeg: H.264, CRF 28 (high compression), fast preset, scale to 720p to save space
    $arguments = "-i `"$inputFile`" -vcodec libx264 -crf 28 -preset fast -vf `"scale=-2:720`" -acodec aac -b:a 128k -loglevel error -y `"$outputFile`""
    
    $process = Start-Process -FilePath $ffmpegExe -ArgumentList $arguments -Wait -NoNewWindow -PassThru
    
    if (Test-Path $outputFile) {
        $newSize = [math]::Round((Get-Item $outputFile).Length / 1MB, 2)
        Write-Host "Success: $($video.Name) is now ${newSize}MB"
        
        # Replace original with compressed
        Remove-Item -Force $inputFile
        Rename-Item -Path $outputFile -NewName $video.Name
    } else {
        Write-Host "Failed to compress $($video.Name)"
    }
}

Write-Host "Cleaning up ffmpeg files..."
Remove-Item -Force $zipFile
Remove-Item -Recurse -Force $extractPath

Write-Host "All videos compressed successfully!"
