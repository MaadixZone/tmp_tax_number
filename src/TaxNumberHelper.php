<?php

namespace Drupal\tmp_tax_number;

/**
 * Helper tax class.
 */
class TaxNumberHelper {

  /**
   * Helper for getting all EU country codes.
   *
   * @return array
   *   List of EU conuntry codes
   */
  public static function getEuCountryCodes() {
    return [
      'AT',
      'BE',
      'BG',
      'CY',
      'CZ',
      'DE',
      'DK',
      'EE',
      'ES',
      'IE',
      'EL',
      'FI',
      'MC',
      'FR',
      'GB',
      'IM',
      'GR',
      'HR',
      'HU',
      'IT',
      'LT',
      'LU',
      'LV',
      'MT',
      'NL',
      'PL',
      'PT',
      'RO',
      'SE',
      'SI',
      'SK',
    ];
  }

}
