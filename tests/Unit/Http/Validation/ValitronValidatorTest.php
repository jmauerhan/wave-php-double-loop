<?php declare(strict_types=1);

namespace Test\Unit\Http\Validation;

use Chirper\Http\Validation\ValitronValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Valitron\Validator AS Valitron;
use Valitron\Validator;

class ValitronValidatorTest extends TestCase
{
    /**
     * @var Valitron|MockObject
     */
    private $valitron;

    public function setUp()
    {
        $this->valitron = $this->createMock(Valitron::class);
        parent::setUp();
    }

    public function testSetRulesPassesRulesIntoValitron()
    {
        $rules = ['my' => 'rules'];
        $this->valitron->expects($this->once())
                       ->method('mapFieldsRules')
                       ->with($rules);

        $validator = new ValitronValidator($this->valitron);
        $validator->setRules($rules);
    }

    public function testIsValidSendsArrayOfDataToValitron()
    {
        $json          = '{"data":"some-data"}';
        $expectedArray = ['data' => 'some-data'];
        $this->valitron->expects($this->once())
                       ->method('withData')
                       ->with($expectedArray)
                       ->willReturnSelf();
        $this->valitron->method('validate')->willReturn(true);
        $validator = new ValitronValidator($this->valitron);
        $validator->isValid($json);
    }

    public function testIsValidReturnsValidateValue()
    {
        $json = '{"data":"some-data"}';
        $this->valitron->method('validate')->willReturn(true);
        $this->valitron->method('withData')->willReturnSelf();
        $validator = new ValitronValidator($this->valitron);
        $this->assertTrue($validator->isValid($json));
    }

    public function testGetErrorsReturnsEmptyArrayForValidData()
    {
        $json = '{"data":"some-data"}';
        $this->valitron->method('validate')->willReturn(true);
        $this->valitron->method('withData')->willReturnSelf();
        $validator = new ValitronValidator($this->valitron);
        $this->assertEquals([], $validator->getErrors($json));
    }

    public function testGetErrorsReturnsFlattenedArrayOfErrorsPer()
    {
        $json = '{"data":"some-data"}';
        $this->valitron->method('validate')->willReturn(true);
        $this->valitron->method('withData')->willReturnSelf();
        $validator = new ValitronValidator($this->valitron);
        $this->assertEquals([], $validator->getErrors($json));
    }
}
