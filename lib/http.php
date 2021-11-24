<?php

namespace BFS;

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

	public static function get ( $url, $options = [ ] ) {
		$queryParameters = empty( $options[ 'params' ] ) ? '' : ( '?' . http_build_query( $options[ 'params' ] ) );
		return self::httpRequest(
			$url . $queryParameters,
			'GET',
			$options[ 'data' ] ?? [ ],
			$options[ 'headers' ] ?? [ ]
		);
	}

	public static function post ( $url, $options = [ ] ) {
		$queryParameters = empty( $options[ 'params' ] ) ? '' : ( '?' . http_build_query( $options[ 'params' ] ) );
		$options[ 'headers' ] = $options[ 'headers' ] ?? [ ];
		if ( is_array( $options[ 'data' ] ) )
			$options[ 'headers' ][ 'Content-Type' ] = 'application/json';

		return self::httpRequest(
			$url . $queryParameters,
			'POST',
			$options[ 'data' ] ?? [ ],
			$options[ 'headers' ]
		);
	}

	public static function httpRequest ( $url, $method = 'GET', $data = [ ], $headerMap = [ ] ) {

		$request = curl_init();
		curl_setopt( $request, CURLOPT_URL, $url );
		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $request, CURLOPT_USERAGENT, 'BFS' );


		if ( $method === 'POST' ) {
			$requestBody = '';
			if ( is_string( $data ) )
				$requestBody = $data;
			else if ( is_array( $data ) )
				$requestBody = json_encode( $data );
			// $requestBody = empty( $data ) ? '' : json_encode( $data );
			curl_setopt( $request, CURLOPT_POSTFIELDS, $requestBody );
			$headerMap[ 'Content-Length' ] = strlen( $requestBody );
		}

		$headers = [
			'Cache-Control: no-cache, no-store, must-revalidate'
		];
		// Prepare the header array in a format that curl accepts
		foreach ( $headerMap as $_hKey => $_hVal )
			$headers[ ] = $_hKey . ':' . $_hVal;

		curl_setopt( $request, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $request, CURLOPT_CUSTOMREQUEST, $method );

		/*
		 * The following is an elaborate dance just to capture and store the headers.
		 * Reference: https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request/41135574#41135574
		 */
		$responseHeaders = [ ];
		curl_setopt( $request, CURLOPT_HEADERFUNCTION, function ( $curl, $header ) use ( &$responseHeaders ) {
			[ $name, $value ] = array_merge( explode( ':', $header, 2 ), [ null ] );
			if ( empty( $value ) or empty( $name ) )
				return strlen( $header );

			$responseHeaders[ strtolower( trim( $name ) ) ] = trim( $value );

			return strlen( $header );
		} );

		// Finally, make the HTTP request
		$rawResponse = curl_exec( $request );
		curl_close( $request );

		// Package the response in our custom HTTPResponse class
		return new HTTPResponse( $rawResponse, $responseHeaders );

	}

}

class HTTPResponse {

	public function __construct ( $response, $headers ) {
		$this->response = $response;
		$this->headers = $headers;
	}

	public function getRaw ( $response ) {
		return $this->response;
	}

	public function getAsArray () {
		if ( empty( $this->responseAsArray ) )
			$this->responseAsArray = json_decode( $this->response, true );
		return $this->responseAsArray;
	}

	public function header ( $name ) {
		return $this->headers[ $name ] ?? null;
	}

}
