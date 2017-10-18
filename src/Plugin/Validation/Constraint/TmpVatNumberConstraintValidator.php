<?php

namespace Drupal\tmp_tax_number\Plugin\Validation\Constraint;

use Drupal\address\Plugin\Field\FieldType\AddressItem;
use Drupal\tmp_tax_number\TaxNumberHelper;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\profile\Entity\Profile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Checks if the Vat number is valid.
 */
class TmpVatNumberConstraintValidator extends ConstraintValidator {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {

    if ($constraint->validateVAT) {
      /* @var \Drupal\Core\Entity\Plugin\DataType\EntityAdapter $root */
      $root = $this->context->getRoot();

      /* @var \Drupal\profile\Entity\Profile $profile */
      $profile = $root->getValue() instanceof Profile ? $root->getValue() : FALSE;
      /* @var AddressItem $address */
      $address = $profile->hasField('address') ? $profile->get('address')
        ->first() : FALSE;

      $country_code = $address instanceof AddressItem ? $address->getCountryCode() : FALSE;

      $tmp_tax_number_country_code = mb_substr($value, 0, 2);

      $tmp_tax_number = is_numeric($tmp_tax_number_country_code) ? $value : mb_substr($value, 2);

      if ($country_code && !is_numeric($tmp_tax_number_country_code)) {
        if ($country_code != $tmp_tax_number_country_code &&
          (in_array($tmp_tax_number_country_code, TaxNumberHelper::getEuCountryCodes())
            || in_array($country_code, TaxNumberHelper::getEuCountryCodes()))
        ) {
          $this->context->addViolation($constraint->countryNotMatching);
        }
      }
      elseif (!is_numeric($tmp_tax_number_country_code)) {
        $country_code = $tmp_tax_number_country_code;
      }

      if ($country_code && in_array($country_code, TaxNumberHelper::getEuCountryCodes())) {
        try {
          $client = new \SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
          $response = $client->checkVat([
            'countryCode' => $country_code,
            'vatNumber' => $tmp_tax_number,
          ]);
          $valid = $response->valid;
          if (!$valid) {
            $this->context->addViolation($constraint->incorrectVat);
          }
        }
        catch (\SoapFault $e) {
          watchdog_exception('vat_number', $e);
        }
      }
    }
  }

}
