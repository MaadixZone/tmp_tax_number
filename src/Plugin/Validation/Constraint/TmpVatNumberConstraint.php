<?php

namespace Drupal\tmp_tax_number\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks if a vat number is valid.
 *
 * @Constraint(
 *   id = "TmpVatNumber",
 *   label = @Translation("Check if Vat Number is valid", context =
 *   "Validation"),
 * )
 */
class TmpVatNumberConstraint extends Constraint {

  /**
   * Whether or not to performa validation of vat number on VIES.
   *
   * @var int
   */

  public $validateVAT = 0;

  /**
   * Violation message.
   *
   * @var string
   */
  public $incorrectVat = "Your current VAT number is incorrect.";

  /**
   * Violation message.
   *
   * @var string
   */
  public $countryNotMatching = "Country code on billing address and VAT number Country code not matching.";

}
