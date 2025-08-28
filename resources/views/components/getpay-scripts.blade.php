<script>
    function initiateCheckout() {
        const price = Number('{{ $amount }}')
        const currency = '{{ $currency }}'

        const options = {
            papInfo: "{{ config('getpay.pap_info') }}",
            insKey: "{{ config('getpay.ins_key') }}",
            oprKey: "{{ config('getpay.opr_key') }}",
            clientRequestId: "{{ $refID }}",
            websiteDomain: "{{ config('getpay.merchant_url') }}",
            callbackUrl: {
                successUrl: "{{ $callbackUrl }}",
                failUrl: "{{ $failUrl }}",
            },
            price,
            currency,
            paymentMethod: null,
            userInfo: {
                name: "",
                email: "",
                state: "",
                country: "",
                zipcode: "",
                city: "",
                address: "",
            },
            prefill: {},
            disableFields: {},
            themeColor: '{{ $themeColor }}',
            businessName: '{{ $businessName }}',
            imageUrl: '{{ $imageUrl }}',
            orderInformationUI: `{{ $orderInfoUi }}`,
            onSuccess: () => {
                document.dispatchEvent(new Event('getpay-success', {
                    bubbles: true,
                    cancelable: true,
                }))
                location.href = '{{ route('getpay.payment') }}'
            },
            onError: (error) => {
                document.dispatchEvent(new Event('getpay-error', {
                    bubbles: true,
                    cancelable: true,
                }))
                console.log(error)
            }
        }

        options.baseUrl = "{{ config('getpay.base_url') }}"
        const getpay = new GetPay(options)
        getpay.initialize()
    }

    document.addEventListener('DOMContentLoaded', () => {
        console.log(typeof GetPay)
        initiateCheckout()
    })
</script>
