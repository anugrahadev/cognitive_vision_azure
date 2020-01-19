<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=" . getenv('account_name') . ";AccountKey=" . getenv('account_key');
$containerName = "anugrahacontainer";
// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
if (isset($_POST['submit'])) {
	$uploadedfile = strtolower($_FILES["upload"]["name"]);
	$content = fopen($_FILES["upload"]["tmp_name"], "r");
	// echo fread($content, filesize($fileToUpload));
	$blobClient->createBlockBlob($containerName, $uploadedfile, $content);
	header("Location: index.php");
}
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!DOCTYPE html>
<html>

<head>
	<title>Submission 2 MACD</title>

	<link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

	<!-- Bootstrap core CSS -->
	<link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="starter-template.css" rel="stylesheet">
</head>

<body>
	<main role="main" class="container">
		<div class="starter-template"> <br>
			<h1>Submission 2 MACD Storage & Cognitive Computer Vision</h1>
			<p>Silahkan pilih dan upload gambar lalu tekan tombol "Analyze" pada gambar yang dipilih</p>
		</div>
		<div class="mt-4 mb-2">
			<form class="d-flex justify-content-lefr" action="index.php" method="post" enctype="multipart/form-data">
				<input type="file" name="upload" accept=".png,.jpeg,.jpg." required>
				<input type="submit" name="submit" value="Upload">
			</form>
		</div>
		<br>
		<br>
		<table class="table table-striped table-responsive">

			<thead class="thead-dark">
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>URL</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1; ?>
				<?php
				do {
					foreach ($result->getBlobs() as $blob) {
				?>
						<tr>
							<td scope="row"><?= $i; ?></td>
							<td><?php echo $blob->getName() ?></td>
							<td><?php echo $blob->getUrl() ?></td>
							<td>
								<form action="cognitive_visiondicoding.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl() ?>">
									<input type="submit" name="submit" value="Analyze!" class="btn btn-primary">
								</form>
							</td>
						</tr>
				<?php
						$i++;
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while ($result->getContinuationToken());
				?>

			</tbody>
		</table>

		</div>

		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script>
			window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')
		</script>
		<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
		<script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
</body>

</html>