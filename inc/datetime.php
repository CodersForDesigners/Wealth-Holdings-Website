<?php
/*
 *
 * reference:
 * https://stackoverflow.com/questions/37541458/php-function-for-convert-date-time-to-excel-number-datevalue-conversion
 *
 */

namespace CFD;

class DateTime {

	public static function getSpreadsheetDateFromISO8601 ( $iso8601 ) {
		$date = self::parseISO8601( $iso8601 );
		$IST_Date = self::toIST( $date );
		$dateValue = self::getDateValue( $IST_Date );
		$timeValue = self::getTimeValue( $IST_Date );
		$spreadsheetDate = $dateValue + $timeValue;
		return $spreadsheetDate;
	}
		private static function parseISO8601 ( $iso8601 ) {
			return \DateTime::createFromFormat(
				'Y-m-d\TH:i:s.u\Z',
				$iso8601,
				new \DateTimeZone( 'UTC' )
			);
		}
		private static function toIST ( $date ) {
			$date->setTimeZone( new \DateTimeZone( 'Asia/Kolkata' ) );
			return $date;
		}
		// Implementation of the `DATEVALUE` function in spreadsheet software
		private static function getDateValue ( $date ) {
			// $modifier = 2;
			// $dateAt1900__millisecondsSinceEpoch = self::parseISO8601( '1900-01-01T00:00:00.000Z' )->getTimestamp() * 1000;

			// $millisecondsSinceEpoch = $date->getTimestamp() * 1000;

			// if ( $millisecondsSinceEpoch <= -2203891200000 )
			// 	$modifier = 1;

			// $dateValue = ceil(
			// 	( $millisecondsSinceEpoch - $dateAt1900__millisecondsSinceEpoch )
			// 	/ 86400000
			// ) + $modifier;

			$timePlusOneDay = strtotime( $date->format( 'Y-m-d H:i:s.u' ) . ' + 1 day' );
			$time = strtotime( date( 'Y-m-d', $timePlusOneDay ) );
			$dateValue = intval( 25569 + $time / 86400 );

			return $dateValue;
		}

		// Implementation of the `TIMEVALUE` function in spreadsheet software
		private static function getTimeValue ( $date ) {
			$hours = (int) $date->format( 'H' );
			$minutes = (int) $date->format( 'i' );
			$seconds = (int) $date->format( 's' );
			$timeValue = (
				3600 * $hours
				+ ( 60 * $minutes )
				+ $seconds
			) / 86400;
			return $timeValue;
		}

}
