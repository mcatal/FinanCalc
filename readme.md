#FinanCalc

A lightweight, simple and easy PHP library for calculating annuities (e.g., mortgages) according to various input data

[![Composer package](https://img.shields.io/packagist/v/uruba/financalc.svg)](https://packagist.org/packages/uruba/financalc)
[![Build Status](https://travis-ci.org/uruba/FinanCalc.svg?branch=master)](https://travis-ci.org/uruba/FinanCalc)

## Requirements
* PHP 5.5+
* BCMath module
* PHPUnit for testing

## Features
* Annuity present and future value calculator
* Debt amortization calculator
* Bond fair (market) value calculator
* Bond Yield-to-Maturity calculator

Much more to come – including calculators for discount securities, ~~bond valuation~~, duration, stock pricing...

>**Please bear in mind that this is an ALPHA version containing incomplete features. The codebase is prone to drastic changes during its way out of the alpha stage.**

## Learning the ropes

### Place the library files into your directory structure

Just copy all the files somewhere appropriate, like a dedicated "*vendor*" or "*lib*" directory (so that it doesn't make a mess out of your directory hierarchy). Nothing more is needed.

Alternatively, you can obtain the library as a package via Composer. It's hosted on [Packagist](https://packagist.org/packages/uruba/financalc)

### Include it in your project

The initialization is dead simple. Just include the main **FinanCalc.php** file and you are good to go!

```php
// replace the example Composer-bound path with yours
require_once dirname(__FILE__) . '/vendor/uruba/financalc/src/FinanCalc.php';
```

### Instantiation

You have two choices as to how to instantiate the appropriate class to get your results:

#### Factory methods

Since the library automatically keeps track of pre-defined factory methods (contained in the classes which are members of the namespace *FinanCalc\Calculators\Factories*), it's very easy and straightforward to utilize them.

From the main *FinanCalc* object (whose instance you get by calling its static method *getInstance()*) you have to call the *getFactory()* method, which takes in the name of the factory class as a parameter of type *string* (you can find all the included factory classes in the *src/calculators/factories* directory).

This method yields you the factory object, on which you can finally call the target factory method that produces the appropriate calculator instance for you.

```php
use FinanCalc\FinanCalc;

...

$annuityCalculatorFactory = FinanCalc
    ::getInstance()
    ->getFactory('DebtAmortizatorFactory')
    ->newYearlyDebtAmortizationInArrears(
        40000,
        6,
        0.12);
```

#### Direct instantiation

The second option is to instantiate the calculator class of your choice directly by calling its constructor with appropriate parameters (you can find all the included calculator classes in the *src/calculators* directory).

```php
use FinanCalc\Calculators\DebtAmortizator;
use FinanCalc\Constants\AnnuityPaymentTypes;

...

$annuityCalculatorDirect = new DebtAmortizator(
                                       40000,
                                       6,
                                       0.12,
                                       360,
                                       new AnnuityPaymentTypes(AnnuityPaymentTypes::IN_ARREARS));
```

### Getting results

It's very simple to retrieve the results. Every calculator class implementing the *CalculatorInterface* has a getter method *getResult()*, which enables you to get an appropriate object representing the result of the calculation according to the data passed earlier to the constructor/factory method of a given calculator class.

We'll demonstrate the process on our *AnnuityCalculator* – step by step, day by day:

1. step is to instantiate the appropriate calculator class, either by constructor or by a factory method (refer to the previous chapter for more information)

    ```php
    use \FinanCalc\FinanCalc;
    
    ...
    
    // Instantiation by a factory method 
    // – 
    // in our case we calculate a yearly-compounded annuity
    // with a duration of 5 periods (here years),
    // 100000 money units paid out per period
    // and a compounding interest rate of 0.15 (i.e., 15%)
    $annuityCalculatorObject = FinanCalc
                                    ::getInstance()
                                    ->getFactory('AnnuityCalculatorFactory')
                                    ->newYearlyAnnuity(
                                        100000, 
                                        5, 
                                        0.15);
    ```

2. step is to get the mentioned "result" object:

    ```php
    $result = $annuityCalculatorObject->getResult();
    ```

3. step is to get the result by exploiting appropriate getter methods (for a detailed list of available gettter methods please refer to the **Reference** chapter)

    ```php
    // get the present value of the annuity in arrears
    // (as a string)
    $PV = $result->getPresentValue(
                        new AnnuityPaymentTypes(AnnuityPaymentTypes::IN_ARREARS)
                    );
    // get the future value of the annuity in arrears
    // (as a string)
    $FV = $result->getFutureValue(
                        new AnnuityPaymentTypes(AnnuityPaymentTypes::IN_ARREARS)
                    );
    ```

Therewith the process is concluded and you can now use the obtained results in any way you see fit.


### Configuration

The configuration capabilities are currently very limited so there's next to nothing to tinker with.

The default configuration values are currently to be found in the "*constants/Default.php*" file, but there will be a possibility to use an easily accessible JSON configuration file in the future.

### Tests

The library includes a "*test*" subdirectory which contains all the basic tests. For your peace of mind, feel free to give them a run on your setup (provided that you have PHPUnit good and ready) and ensure that everything checks out.

The tests currently cover only ~50% of the library's code so they're also a subject of necessary future improvements.

## Reference

Here you can find the documentation for each of the vanilla calculator types.

The implicit type of setters'/constructors' arguments as well as getters' returned values is String if not stated otherwise.

### AnnuityCalculator
namespace `FinanCalc\Calculators`
* **__construct($annuitySinglePaymentAmount, $annuityNoOfCompoundingPeriods, $annuityInterest, $annuityPeriodLength)**
  * *$annuitySinglePaymentAmount* = **'K'** – amount of each individual payment (number greater than zero)
  * *$annuityNoOfCompoundingPeriods* = **'n'** – number of periods pertaining to the interest compounding; if 'n = 0', the annuity is considered a perpetuity
  * *$annuityInterest* = **'i'** – the interest rate by which the unpaid balance is multiplied (i.e., a decimal number typically lower than 1 and greater than 0)
  * *$annuityPeriodLength* = length of a single period in days (number greater than zero)
* **getResult()** – gets the ***AnnuityInstance*** object manufactured by the constructor

#### AnnuityCalculatorFactory (*AnnuityCalculator's factory object'*)
namespace `FinanCalc\Calculators\Factories`
* **newYearlyAnnuity($annuitySinglePaymentAmount, $annuityNoOfCompoundingPeriods, $annuityInterest)**
* **newMonthlyAnnuity($annuitySinglePaymentAmount, $annuityNoOfCompoundingPeriods, $annuityInterest)**
* **newDailyAnnuity($annuitySinglePaymentAmount, $annuityNoOfCompoundingPeriods, $annuityInterest)**
* **newPerpetuity($annuitySinglePaymentAmount, $annuityInterest)**

#### AnnuityInstance (*AnnuityCalculator's result object*)
namespace `FinanCalc\Calculators\AnnuityCalculator`
##### Setters
* **setAnnuitySinglePaymentAmount($annuitySinglePaymentAmount)** – sets K
* **setAnnuityNoOfCompoundingPeriods($annuityNoOfCompoundingPeriods)** – sets n
* **setAnnuityInterest($annuityInterest)** – sets i
* **setAnnuityPeriodLength($annuityPeriodLength)** – sets the length of each compounding period in days

##### Getters
* **getAnnuitySinglePaymentAmount()** – gets K
* **getAnnuityNoOfCompoundingPeriods()** – gets n
* **getAnnuityInterest()** – gets i
* **getAnnuityPeriodLengthInYears()** – gets the length of each compounding periods in years
* **getAnnuityPeriodLengthInMonths()** – gets the length of each compounding periods in months
* **getAnnuityPeriodLengthInDays()** – gets the length of each compounding periods in days
* **getPresentValue(AnnuityPaymentTypes $annuityType)** – gets the present value of the annuity
  * *AnuityPaymentTypes $annuityType* = determines whether the payments are made either at the beginning or the end of each of the annuity's periods
  [*optional for perpetuities*]
* **getFutureValue(AnnuityPaymentTypes $annuityType)** – gets the future value of the annuity
  * *AnuityPaymentTypes $annuityType* = determines whether the payments are made either at the beginning or the end of each of the annuity's periods
  [*optional for perpetuities*]
* **getValue(AnnuityPaymentTypes $annuityPaymentType, AnnuityValueTypes $annuityValueType)** – gets either the present or the future value of the annuity
  * *AnuityPaymentTypes $annuityPaymentType* = determines whether the payments are made either at the beginning or the end of each of the annuity's periods
  [*optional for perpetuities*]
  * *AnuityValueTypes $annuityValueType* = determines whether the result is the present or the future value of the annuity

#### AnnuityPaymentTypes
namespace `FinanCalc\Constants`
* *IN_ADVANCE* = 1
* *IN_ARREARS* = 2

#### AnnuityValueTypes
namespace `FinanCalc\Constants`
* *PRESENT_VALUE* = 1
* *FUTURE_VALUE* = 2

* * *

### DebtAmortizator
namespace `FinanCalc\Calculators`
* **__construct($debtPrincipal, $debtNoOfCompoundingPeriods, $debtPeriodLength, $debtInterest, AnnuityPaymentTypes $debtPaymentType)**
  * *$debtPrincipal* = **'PV'** – the principal of the debt (number greater than zero)
  * *$debtNoOfCompoundingPeriods* = **'n'** – number of the debt's compounding periods (number greater than zero)
  * *$debtPeriodLength* = length of each of the debt's compounding periods in days (number greater than zero)
  * *$debtInterest* = **'i'** – interest by which the outstanding balance is multiplied (i.e., a decimal number typically lower than 1 and greater than 0)
  * *AnnuityPaymentTypes $debtPaymentType* = determines whether the debt is paid in advance (at the beginning of each period) or in arrears (in the end of each period)
* **getResult()** – gets the ***DebtInstance*** object manufactured by the constructor

#### DebtAmortizatorFactory (*DebtAmortizator's factory object'*)
namespace `FinanCalc\Calculators\Factories`
* **newYearlyDebtAmortizationInArrears($debtPrincipal, $debtNoOfPeriods, $debtInterest)**
* **newMonthlyDebtAmortizationInArrears($debtPrincipal, $debtNoOfPeriods, $debtInterest)**
* **newDailyDebtAmortizationInArrears($debtPrincipal, $debtNoOfPeriods, $debtInterest)**
* **newDebtAmortizationInArrearsCustomPeriodLength($debtPrincipal, $debtNoOfPeriods, $debtInterest, $debtSinglePeriodLength)**
* **newYearlyDebtAmortizationInAdvance($debtPrincipal, $debtNoOfPeriods, $debtInterest)**
* **newMonthlyDebtAmortizationInAdvance($debtPrincipal, $debtNoOfPeriods, $debtInterest)**
* **newDailyDebtAmortizationInAdvance($debtPrincipal, $debtNoOfPeriods, $debtInterest)**
* **newDebtAmortizationInAdvanceCustomPeriodLength($debtPrincipal, $debtNoOfPeriods, $debtInterest, $debtSinglePeriodLength)**

#### DebtInstance (*DebtAmortizator's result object*)
namespace `FinanCalc\Calculators\DebtAmortizator`
##### Setters
* **setDebtPrincipal($debtPrincipal)** – sets PV
* **setDebtNoOfCompoundingPeriods($debtNoOfCompoundingPeriods)** – sets n
* **setDebtPeriodLength($debtPeriodLength)** – sets the length of each compounding period in days
* **setDebtInterest($debtInterest)** – sets i
* **setDebtPaymentType(AnnuityPaymentTypes $debtPaymentType)** – determines whether the compounding is done in advance or in arrears

##### Getters
* **getDebtDiscountFactor()** – gets the value of the debt's discount factor = **'v'**
* **getDebtSingleRepayment()** – gets the amount of a single repayment = **'K'**
* **getDebtPrincipal()** – gets PV
* **getDebtNoOfCompoundingPeriods()** – gets n
* **getDebtPeriodLengthInYears()**  – gets the length of each compounding period in years
* **getDebtPeriodLengthInMonths()**  – gets the length of each compounding period in months
* **getDebtPeriodLengthInDays()** – gets the length of each compounding period in days
* **getDebtDurationInYears()** – gets the duration of the debt in years
* **getDebtDurationInMonths()** – gets the duration of the debt in months
* **getDebtDurationInDays()** – gets the duration of the debt in days
* **getDebtInterest()** – gets i
* **getDebtRepayments()** – gets the **array of RepaymentInstance** objects representing all the individual payments within the debt comprised into an array

#### RepaymentInstance
namespace `FinanCalc\Calculators\DebtAmortizator`
* **getPrincipalAmount()** – gets the amount of the debt's principal covered by this single repayment
* **getInterestAmount()** – gets the amount of the debt's interest covered by this single repayment
* **getTotalAmount()** – gets the total amount covered by this individual repayment (both the "principal" and "interest" part)

* * *

### BondFairValueCalculator
namespace `FinanCalc\Calculators`
* **__construct($bondFaceValue, $bondAnnualCouponRate, $bondVIR, $bondYearsToMaturity, $bondPaymentFrequency = 1)**
  * *$bondFaceValue* = **'F'** – face value of the bond (number greater than zero)
  * *$bondAnnualCouponRate* = **'c'** – annual coupon rate of the bond (i.e., a decimal number typically lower than 1 and greater than 0)
  * *$bondVIR* = **'i' or 'VIR'** – valuation interest rate of the bond (i.e., a decimal number typically lower than 1 and greater than 0)
  * *bondYearsToMaturity* = number of years to the maturity of the bond (number greater than zero, can be a decimal number)
  * *bondPaymentFrequency* = frequency of bond payments (expressed in a divisor of 12 months ~ 1 year); e.g.: divisor 2 means semi-annual payments
* **getResult()** – gets the ***BondInstance*** object manufactured by the constructor

#### BondFairValueCalculatorFactory (*BondFairValueCalculator's factory object'*)
namespace `FinanCalc\Calculators\Factories`
* **newAnnualCouponsBond($bondFaceValue, $bondAnnualCouponRate, $bondVIR, $bondYearsToMaturity)**
* **newSemiAnnualCouponsBond($bondFaceValue, $bondAnnualCouponRate, $bondVIR, $bondYearsToMaturity)**
* **newQuarterlyCouponsBond($bondFaceValue, $bondAnnualCouponRate, $bondVIR, $bondYearsToMaturity)**
* **newMonthlyCouponsBond($bondFaceValue, $bondAnnualCouponRate, $bondVIR, $bondYearsToMaturity)**
* **newCustomCouponFrequencyBond($bondFaceValue, $bondAnnualCouponRate, $bondVIR, $bondYearsToMaturity, $bondPaymentFrequency)**

#### BondInstance (*BondFairValueCalculator's result object*)
namespace `FinanCalc\Calculators\BondFairValueCalculator`
##### Setters
* **setBondFaceValue($bondFaceValue)** – sets F
* **setBondAnnualCouponRate($bondAnnualCouponRate)** – sets c
* **setBondVIR($bondVIR)** – sets i/VIR
* **setBondYearsToMaturity($bondYearsToMaturity)** – sets the number of years to the maturity of the bond
* **setBondPaymentFrequency($bondPaymentFrequency)** – sets the frequency of bond payments

##### Getters
* **getBondFaceValue()** – gets F
* **getBondAnnualCouponRate()** – gets c
* **getBondVIR()** – gets i/VIR
* **getBondYearsToMaturity()** – gets the number of years to the maturity of the bond
* **getBondPaymentFrequncy()** – gets the frequency of bond payments
* **getBondNoOfPayments()** – gets the total number of payments during the lifespan of the bond
* **getBondFairValue()** – gets the fair (market) value of the bond [calculated as present value of future cashflows corresponding to the bond by means of the valuation interest rate]

* * *

### BondYTMCalculator
namespace `FinanCalc\Calculators`
* **__construct($bondFaceValue, $bondMarketValue, $bondAnnualCouponRate, $bondYearsToMaturity, $bondPaymentFrequency = 1)**
  * *$bondFaceValue* = **'F'** – face value of the bond (number greater than zero)
  * *$bondMarketValue* = **'P'** – market value (i.e., price) of the bond (number greater than zero)
  * *$bondAnnualCouponRate* = **'c'** – annual coupon rate of the bond (i.e., a decimal number typically lower than 1 and greater than 0)
  * *bondYearsToMaturity* = number of years to the maturity of the bond (number greater than zero, can be a decimal number)
  * *bondPaymentFrequency* = frequency of bond payments (expressed in a divisor of 12 months ~ 1 year); e.g.: divisor 2 means semi-annual payments
* **getResult()** – gets the ***BondInstance*** object manufactured by the constructor

#### BondYTMCalculatorFactory (*BondYTMCalculator's factory object'*)
namespace `FinanCalc\Calculators\Factories`
* **newAnnualCouponsBond($bondFaceValue, $bondMarketValue, $bondAnnualCouponRate, $bondYearsToMaturity)**
* **newSemiAnnualCouponsBond($bondFaceValue, $bondMarketValue, $bondAnnualCouponRate, $bondYearsToMaturity)**
* **newQuarterlyCouponsBond($bondFaceValue, $bondMarketValue, $bondAnnualCouponRate, $bondYearsToMaturity)**
* **newMonthlyCouponsBond($bondFaceValue, $bondMarketValue, $bondAnnualCouponRate, $bondYearsToMaturity)**
* **newCustomCouponFrequencyBond($bondFaceValue, $bondMarketValue, $bondAnnualCouponRate, $bondYearsToMaturity, $bondPaymentFrequency)**

#### BondInstance (*BondYTMCalculator's result object*)
namespace `FinanCalc\Calculators\BondYTMCalculator`
##### Setters
* **setBondFaceValue($bondFaceValue)** – sets F
* **setBondMarketValue($bondMarketValue)** – sets the market value of the bond
* **setBondAnnualCouponRate($bondAnnualCouponRate)** – sets c
* **setBondYearsToMaturity($bondYearsToMaturity)** – sets the number of years to the maturity of the bond
* **setBondPaymentFrequency($bondPaymentFrequency)** – sets the frequency of bond payments

##### Getters
* **getBondFaceValue()** – gets F
* **getBondMarketValue()** – gets the market value of the bond
* **getBondAnnualCouponRate()** – gets c
* **getBondYearsToMaturity()** – gets the number of years to the maturity of the bond
* **getBondPaymentFrequncy()** – gets the frequency of bond payments
* **getBondNoOfPayments()** – gets the total number of payments during the lifespan of the bond
* **getApproxBondYTM()** – gets the approximate value of the bond's yield to maturity in the form of a decimal number [it is the internal rate of return of the bond]


* * *

## DISCLAIMER
You are free to use/modify/extend the library as you please - for it to serve your purpose. As per the (un)license, the software is provided as is and the original author cannot be held liable for any losses/damages directly or indirectly resulting from using thereof.
Attribution is welcome, but certainly not required.

**NOTE**
The library is currently work-in-progress and it is certain that new features will be added in the process.Consider this, therefore, as a preview product prone to abrupt and extensive changes that may affect functionality of an external code adapted to a prior version(s) of the library.
Always explore the provisional compatibility of the library with your project in case you upgrade to a new version of the library (by means of an extensive testing of the code in which you are exerting the library's features).



