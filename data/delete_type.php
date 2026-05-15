<?php 
header('Content-Type: application/json; charset=utf-8');
require_once('../class/Laundry.php');
$return = ['valid' => false, 'msg' => 'Invalid request.'];

if(isset($_POST['id'])){
	$id = intval($_POST['id']);
	try {
		$result = $laundry->delete_type($id);
		if($result) {
			$return['valid'] = true;
			$return['msg'] = 'Laundry type deleted successfully!';
		} else {
			$return['msg'] = 'Failed to delete laundry type.';
		}
	} catch (Exception $e) {
		$return['valid'] = false;
		if(strpos($e->getMessage(), 'foreign key constraint fails') !== false){
			$return['msg'] = 'Cannot delete this type because it is currently linked to laundry orders.';
		} else {
			$return['msg'] = 'Database Error: ' . $e->getMessage();
		}
	}
}

echo json_encode($return);
$laundry->Disconnect();
?>
