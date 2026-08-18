<?php




namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LoanApplication extends Model
{
    protected $fillable = [
        'application_no',
        'customer_id',
        'business_id',
        'loan_product_id',
        'loan_amount',
        'duration_months',
        'purpose',
        'status',
        'remarks',
        'application_date',
    ];

    protected $casts = [
        'loan_amount'      => 'decimal:2',
        'application_date' => 'date',
    ];

    const STATUSES = [
        'draft',
        'submitted',
        'under_review',
        'approved',
        'rejected',
        'disbursed',
        'closed',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function coSigner(): HasOne
    {
        return $this->hasOne(CoSigner::class, 'application_id');
    }

    /**
     * The saved Monthly / Total / Interest breakdown for this application.
     */
    public function loanAmount(): HasOne
    {
        return $this->hasOne(LoanAmount::class);
    }

    public static function generateApplicationNo(): string
    {
        $prefix = 'LN';
        $year   = date('Y');
        $last   = self::latest('id')->first();
        $number = $last ? intval(substr($last->application_no, -5)) + 1 : 1;

        return sprintf('%s%s%05d', $prefix, $year, $number);
    }

    public function collaterals(): HasMany
    {
        return $this->hasMany(Collateral::class, 'application_id');
    }

    public function coSigners(): HasMany
    {
        return $this->hasMany(CoSigner::class, 'application_id');
    }

    public function customerByStaff()
    {
        return $this->hasOneThrough(
            CustomerByStaff::class,
            Customer::class,
            'id',           // customers.id
            'customer_id',  // customer_by_staff.customer_id
            'customer_id',  // loan_applications.customer_id
            'id'            // customers.id
        );
    }

    /**
     * Create a LoanApplication AND its LoanAmount breakdown together,
     * so every creation flow (staff API, website form, future flows)
     * always ends up with a saved Monthly/Total/Interest record.
     */
    public static function createWithAmount(array $data, float $interestRate): self
    {
        $application = self::create($data);

        LoanAmount::createFor($application, $interestRate);

        return $application;
    }
}