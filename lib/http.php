<?php

class HTTP {

	// this function supports a variety of parameter permutations
	public static function respond ( $one = null, $two = null, $three = null ) {
		$code = 200;
		$message = null;
		$data = null;

		// Determine what each parameter is
		if ( is_string( $one ) ) {
			$message = $one;
			if ( is_numeric( $two ) )
				$code = $two;
			if ( is_array( $two ) )
				$data = $two;
			if ( is_numeric( $three ) )
				$code = $three;
		}
		else if ( is_array( $one ) ) {
			$data = $one;
			if ( is_numeric( $two ) )
				$code = $two;
		}

		http_response_code( $code );

		$response = [
			'code' => $code
		];
		if ( ! empty( $message ) )
			$response[ 'message' ] = $message;
		if ( ! empty( $data ) )
			$response[ 'data' ] = $data;

		echo json_encode( $response );
	}

}
