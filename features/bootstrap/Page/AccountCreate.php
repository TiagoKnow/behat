<?php

namespace Page;

class AccountCreate extends AbstractPage
{
    const TYPE_PERSON = 'person';
    const TYPE_COMPANY = 'company';

    /**
     * @var string $path
     */
    public $path = '/customer/account/create/';

    private $keepPassword;

    /**
     * @param null $firstName
     * @return $this
     */
    public function setFirstName($firstName = null)
    {
        $value = $this->setValueOrUseFaker($firstName, 'firstName');
        $this->fillField('RegistrationForm[first_name]', $value);

        return $this;
    }

    /**
     * @param null $lastName
     * @return $this
     */
    public function setLastName($lastName = null)
    {
        $value = $this->setValueOrUseFaker($lastName, 'lastName');
        $this->fillField('RegistrationForm[last_name]', $value);

        return $this;
    }

    /**
     * @param null $legalName
     * @return $this
     */
    public function setLegalName($legalName = null)
    {
        $value = sprintf(
            '%s %s',
            $this->setValueOrUseFaker($legalName, 'company'),
            $this->setValueOrUseFaker($legalName, 'companySuffix')
        );
        $this->fillField('RegistrationForm[legal_name]', $value);

        return $this;
    }

    /**
     * @param null $fantasyName
     * @return $this
     */
    public function setFantasyName($fantasyName = null)
    {
        $value = $this->setValueOrUseFaker($fantasyName, 'company');
        $this->fillField('RegistrationForm[fantasy_name]', $value);

        return $this;
    }

    /**
     * @param null $cpf
     * @return $this
     */
    public function setCpf($cpf = null)
    {
        $value = $this->setValueOrUseFaker($cpf, 'cpf');
        $this->fillFieldWithMask('#RegistrationForm_tax_identification', $value);

        return $this;
    }

    /**
     * @param null $cnpj
     * @return $this
     */
    public function setCnpj($cnpj = null)
    {
        $value = null === $cnpj ? $this->getFaker()->cnpj : $cnpj;
        $element = $this->find('css', '#RegistrationForm_company_tax_identification');
        $element->click();
        $element->setValue($value);

        return $this;
    }

    /**
     * @param null $birthday
     * @return $this
     */
    public function setBirthday($birthday = null)
    {
        $value = null === $birthday ? $this->getFaker()->date('d/m/Y') : $birthday;
        $element = $this->find('css', '#RegistrationForm_birthday');
        $element->click();
        $element->setValue($value);

        return $this;
    }

    /**
     * @param null $email
     * @return $this
     */
    public function setEmail($email = null)
    {
        $value = null === $email ? $this->getFaker()->email : $email;
        $this->fillField('E-mail ', $value);

        return $this;
    }

    /**
     * @param null $companyEmail
     * @return $this
     */
    public function setCompanyEmail($companyEmail = null)
    {
        $value = null === $companyEmail ? $this->getFaker()->companyEmail : $companyEmail;
        $element = $this->find('css', 'div.company-entity #RegistrationForm_email');

        if (!is_null($element)) {
            $element->click();
            $element->setValue($value);
        }

        return $this;
    }
    /**
     * @param null $password
     * @return $this
     */
    public function setPassword($password = null)
    {
        $value = null === $password ? $this->getFaker()->password() : $password;
        $this->keepPassword = $value;
        $this->fillField('RegistrationForm[password]', $value);

        return $this;
    }

    /**
     * @param null $password2
     * @return $this
     */
    public function setPassword2($password2 = null)
    {
        $value = null === $password2 ? $this->keepPassword : $password2;
        $this->fillField('RegistrationForm[password2]', $value);

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type = self::TYPE_PERSON)
    {
        $element = $this->find('css', $type == self::TYPE_PERSON ? '#radioTypePerson' : '#radioTypeCompany');

        if (!is_null($element)) {
            $element->click();
        }

        return $this;
    }

    /**
     * @param string $gender
     * @return $this
     */
    public function setGender($gender = 'm')
    {
        $element = $this->find('css', '#RegistrationForm_gender');

        if (!is_null($element)) {
            $element->selectOption($gender == 'm' ? 'male' : 'female');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function setIsento()
    {
        $this->checkField('RegistrationForm_state_tax_number_free');

        return $this;
    }

    /**
     * @return $this
     */
    public function setCustomerSegment()
    {
        $this->selectFieldOption('RegistrationForm_fk_customer_segment', $this->getFaker()->numberBetween(1,15));

        return $this;
    }

    public function setState()
    {
        $this->selectFieldOption('RegistrationForm_fk_customer_address_region', $this->getFaker()->numberBetween(17,43));

        return $this;
    }

    /**
     * @return $this
     */
    public function createAccount()
    {
        $element = $this->find('css', '#send');

        if (!is_null($element)) {
            $element->click();
        }

        return $this;
    }
}