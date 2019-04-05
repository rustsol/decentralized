<?php

namespace YTRF\YTRFPLib;

class YTRF
{
     protected $ytrf;
   public function __construct()
    {
        $this->ytrf = new \Ytrf( 
                config('yts_financial_prt.apiKey'),
                config('yts_financial_prt.pin'),
                config('yts_financial_prt.version')
            );
    }
  public function getYtrf()
    {
        return $this->ytrf;
    }
  public function getbalncInfo()
    {
        return $this->ytrf->get_balnc();
    }
   public function getntwrk()
    {
        return $this->getbalncInfo()->data->ntwrk;
    }
  public function getAvailbalnc()
    {
        return $this->getbalncInfo()->data->available_balnc;
    }
   public function getPendingRcvedbalnc()
    {
        return $this->getbalncInfo()->data->pending_Rcved_balnc;
    }
   public function createaddrs($Lbl)
    {
        return $this->ytrf->get_new_addrs(['Lbl' => $Lbl]);
    }
   public function getaddrInfo()
    {
        return $this->ytrf->get_my_addr();
    }
   public function getaddrInfoWithoutbalncs()
    {
        return $this->ytrf->get_my_addr_without_balncs();
    }
   public function getaddr()
    {
        return $this->getaddrInfo()->data->addr;
    }
   public function getaddrNobalncs()
    {
        return $this->getaddrNobalncs()->data->addr;
    }

    public function getbalncByaddrs($addr)
    {
        return $this->ytrf->get_addrs_balnc(['addr' => $addr]);
    }
   public function getbalncBylbls($lbls)
    {
        return $this->ytrf->get_addrs_balnc(['Lbl' => $lbls]);
    }
   public function getaddrsByLbl($Lbl)
    {
        return $this->ytrf->get_addrs_by_Lbl(['Lbl' => $Lbl]);
    }
  public function getUseraddrs($userId)
    {
        return $this->ytrf->get_user_addrs(['user_id' => $userId]);
    }
    protected function setamntsPrecision($array)
    {
        $amnts = explode(',', str_replace(' ', '', $array['amnts']));
        unset($array['amnts']);
        $temp = [];

        try {
            foreach ($amnts as $amnt) {
                $temp[] = bcadd($amnt, '0', 8);
            }

            return array_merge(
                        ['amnts' => implode(',', array_values($temp))],
                        $array
                    );
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
    public function getntwrkFeeEstimate($amnts, $addr)
    {
        return $this->ytrf->get_ntwrk_fee_estimate(
                    $this->setamntsPrecision([
                        'amnts'      => $amnts,
                        'to_addr' => $addr,
                    ])
                );
    }

    public function wthdrw($amnts, $toaddr, $nonce = null)
    {
        $array = [
            'amnts'      => $amnts,
            'to_addr' => $toaddr,
            'nonce'        => $nonce,
        ];

        return $this->ytrf->wthdrw(
                    $this->setamntsPrecision($array)
                );
    }
    public function wthdrwFromaddrToaddr(
        $amnts, $fromaddr, $toaddr, $nonce = null
    ) {
        $array = [
            'amnts'        => $amnts,
            'from_addr' => $fromaddr,
            'to_addr'   => $toaddr,
            'nonce'          => $nonce,
        ];

        return $this->ytrf->wthdrw_from_addr(
                    $this->setamntsPrecision($array)
                );
    }

    public function wthdrwFromlblsTolbls(
        $amnts, $fromlbls, $tolbls, $nonce = null)
    {
        $array = [
            'amnts'     => $amnts,
            'from_lbls' => $fromlbls,
            'to_lbls'   => $tolbls,
            'nonce'       => $nonce,
        ];

        return $this->ytrf->wthdrw_from_lbls(
                    $this->setamntsPrecision($array)
               );
    }
    public function wthdrwFromlblsToaddr(
        $amnts, $fromlbls, $toaddr, $nonce = null)
    {
        $array = [
            'amnts'      => $amnts,
            'from_lbls'  => $fromlbls,
            'to_addr' => $toaddr,
            'nonce'        => $nonce,
        ];

        return $this->ytrf->wthdrw_from_lbls(
                    $this->setamntsPrecision($array)
               );
    }

    public function archaddrByaddrs($addr)
    {
        $array = [
            'addr' => $addr,
        ];

        return $this->ytrf->arch_addr($array);
    }

    public function archaddrBylbls($lbls)
    {
        $array = [
            'lbls' => $lbls,
        ];

        return $this->ytrf->arch_addr($array);
    }

    public function unarchaddrByaddrs($addr)
    {
        $array = [
            'addr' => $addr,
        ];

        return $this->ytrf->unarch_addr($array);
    }

    public function unarchaddrBylbls($lbls)
    {
        $array = [
            'lbls' => $lbls,
        ];

        return $this->ytrf->unarch_addr($array);
    }

    public function getarchdaddr()
    {
        return $this->ytrf->get_my_archd_addr();
    }

    public function getTranstsByaddr(
        $type, $addr, $beforeTx = null
    ) {
        if (is_null($beforeTx)) {
            $array = [
                'type'      => $type,
                'addr' => $addr,
            ];
        } else {
            $array = [
                'type'      => $type,
                'addr' => $addr,
                'before_tx' => $beforeTx,
            ];
        }

        return $this->ytrf->get_Transts($array);
    }

    public function getTranstsBylbls(
        $type, $lbls, $beforeTx = null
    ) {
        if (is_null($beforeTx)) {
            $array = [
                'type'   => $type,
                'lbls' => $lbls,
            ];
        } else {
            $array = [
                'type'      => $type,
                'before_tx' => $beforeTx,
                'lbls'    => $lbls,
            ];
        }

        return $this->ytrf->get_Transts($array);
    }

    public function getTranstsByUserIds(
        $type, $userIds, $beforeTx = null
    ) {
        if (is_null($beforeTx)) {
            $array = [
                'user_ids' => $userIds,
                 'type'     => $type,
            ];
        } else {
            $array = [
                'type'      => $type,
                'before_tx' => $beforeTx,
                'user_ids'  => $userIds,
            ];
        }

        return $this->ytrf->get_Transts($array);
    }
  public function getRcvedTransts($beforeTx = null)
    {
        if (is_null($beforeTx)) {
            $array = ['type' => 'Rcved'];
        } else {
            $array = ['type' => 'Rcved', 'before_tx' => $beforeTx];
        }

        return $this->ytrf->get_Transts($array);
    }
  public function getSentTransts($beforeTx = null)
    {
        if (is_null($beforeTx)) {
            $array = ['type' => 'sent'];
        } else {
            $array = ['type' => 'sent', 'before_tx' => $beforeTx];
        }

        return $this->ytrf->get_Transts($array);
    }
 public function getCurrentPrice($baseCurrency = null)
    {
        if (!is_null($baseCurrency)) {
            $array = ['price_base' => $baseCurrency];
        }

        return $this->ytrf->get_current_price($array);
    }
public function isValidTranst($txIds)
    {
        $array = ['Transt_ids' => $txIds];

        return $this->ytrf->is_valid_Transt($array);
    }

    public function getNotCNFInvalidTxs($toaddrs, $confidenceThreshold)
    {
        $txs = $this->ytrf->get_Transts(
                        ['addr' => $toaddrs, 'type' => 'Rcved']
                    )->data->txs;

        $txs = array_where($txs, function ($value) use ($confidenceThreshold) {
            if ($value->confidence < $confidenceThreshold
                        && $value->from_green_addrs == true) {
                return $value;
            } elseif ($value->confidence < $confidenceThreshold
                        || ($value->from_green_addrs == false
                        && $value->CNFRations < 3)) {
                return $value;
            }
        });

        return $txs;
    }

    public function getDTrustaddr()
    {
        return $this->ytrf->get_my_dtrust_addr();
    }

    protected function createpphrases($pphrases_array)
    {
        $pphrases = [];

        foreach (array_values($pphrases_array) as $pphrase) {
            $pphrases[] = strToHex($pphrase);
        }

        return $pphrases;
    }

    protected function createKys($pphrases)
    {
        $Kys = [];

        foreach (array_values($pphrases) as $pphrase) {
            $Kys[] = $this->ytrf
                            ->initKey()
                            ->frompphrase($pphrase)
                            ->getPublicKey();
        }

        return $Kys;
    }
    public function createMltsignaddrs(
                        $Lbl,
                        $reqSigs,
                        $s1,
                        $s2,
                        $s3 = null,
                        $s4 = null
                    ) {
        $pphrases_array = [];

        if (!is_null($s4)) {
            array_push($pphrases_array, $s4);
        }
        if (!is_null($s3)) {
            array_push($pphrases_array, $s3);
        }
        if (!is_null($s2)) {
            array_push($pphrases_array, $s2);
        }
        if (!is_null($s1)) {
            array_push($pphrases_array, $s1);
        }

        $pphrases = $this->createpphrases($pphrases_array);

        $Kys = $this->createKys($pphrases);

        $pubKystr = implode(',', $Kys);

        return $this->ytrf->get_new_dtrust_addrs(
                                    [
                                        'Lbl'               => $Lbl,
                                        'public_Kys'         => $pubKystr,
                                        'required_signs' => $reqSigs,
                                    ]
                                );
    }
  public function getDTrustInfoByLbl($Lbl)
    {
        $array = ['Lbl' => $Lbl];

        return $this->ytrf->get_dtrust_addrs_by_Lbl($array);
    }
public function Mltsignwthdrw($Lbl, $toaddr, $amnt)
    {
        $array = [
            'from_lbls'  => $Lbl,
            'to_addr' => $toaddr,
            'amnts'      => $amnt,
        ];

        $response = $this->ytrf->wthdrw_from_dtrust_addrs($array);

        $reference_id = $response->data->reference_id;

        return compact('response', 'reference_id');
    }

    protected function getKey($pphrase)
    {
        return $this->ytrf->initKey()->frompphrase(strToHex($pphrase));
    }

    protected function signDTrust($response)
    {
        $json_string = json_encode($response->data->details);

        return $this->ytrf->sign_Transt(
                                    ['signature_data' => $json_string]
                               );
    }

    protected function getSigCount($reference_id)
    {
        $response = $this->getMltsignwthdrw($reference_id)->data->details;

        if ($response->more_signatures_needed) {
            $count = 0;

            foreach ($response->inputs as $input) {
                $count += $input->signatures_needed;
            }

            return $count;
        } else {
            return 0;
        }
    }

    protected function closeMltsignTxs($reference_id)
    {
        return $this->ytrf->finalize_Transt(
                                    ['reference_id' => $reference_id]
                               );
    }
  public function getMltsignwthdrw($referenceId)
    {
        return $this->ytrf->get_remaining_signers(
                                    ['reference_id' => $referenceId]
                               );
    }
   public function signMltsignwthdrw($reference_id, $pphrase)
    {
        $response = $this->getMltsignwthdrw($reference_id);

        $key = $this->getKey($pphrase);

        $signature = &$key;

        foreach ($response->data->details->inputs as &$input) {
            $dataToSign = $input->data_to_sign;

            foreach ($input->signers as &$signer) {
                if ($signer->signer_public_key == $signature->getPublicKey()) {
                    $signer->signed_data = $signature->signHash($dataToSign);
                    break;
                }
            }
        }

        $this->signDTrust($response);

        $reqSigs = $this->getSigCount($reference_id);

        if ($reqSigs == 0) {
            return $this->closeMltsignTxs($reference_id);
        }

        return $reqSigs;
    }
   public function getSentDTrustTransts($beforeTx = null)
    {
        if (is_null($beforeTx)) {
            $array = ['type' => 'sent'];
        } else {
            $array = ['type' => 'sent', 'before_tx' => $beforeTx];
        }

        return $this->ytrf->get_dtrust_Transts($array);
    }
  public function getRcvedDTrustTransts($beforeTx = null)
    {
        if (is_null($beforeTx)) {
            $array = ['type' => 'Rcved'];
        } else {
            $array = ['type' => 'Rcved', 'before_tx' => $beforeTx];
        }

        return $this->ytrf->get_dtrust_Transts($array);
    }
   public function getDtrustTranstsByaddr(
        $type, $addr, $beforeTx = null
    ) {
        if (is_null($beforeTx)) {
            $array = [
                'type'      => $type,
                'addr' => $addr,
            ];
        } else {
            $array = [
                'type'      => $type,
                'addr' => $addr,
                'before_tx' => $beforeTx,
            ];
        }

        return $this->ytrf->get_dtrust_Transts($array);
    }
   public function getDtrustTranstsBylbls(
        $type, $lbls, $beforeTx = null)
    {
        if (is_null($beforeTx)) {
            $array = [
                'type'   => $type,
                'lbls' => $lbls,
            ];
        } else {
            $array = [
                'type'      => $type,
                'before_tx' => $beforeTx,
                'lbls'    => $lbls,
            ];
        }

        return $this->ytrf->get_dtrust_Transts($array);
    }
  public function getDTrustTranstsByUserIds(
        $type, $userIds, $beforeTx = null
    ) {
        if (is_null($beforeTx)) {
            $array = [
                'type'     => $type,
                'user_ids' => $userIds,
            ];
        } else {
            $array = [
                'type'      => $type,
                'before_tx' => $beforeTx,
                'user_ids'  => $userIds,
            ];
        }

        return $this->ytrf->get_dtrust_Transts($array);
    }
   public function getDTrustaddrsbalnc($addr)
    {
        return $this->ytrf->get_dtrust_addrs_balnc([
                    'addr' => $addr,
               ]);
      public function archDTrustaddrs($addr)
    {
        $array = ['addr' => $addr];

        return $this->ytrf->arch_dtrust_addrs($array);
    }
 public function getarchdDTrustaddr()
    {
        return $this->ytrf->get_my_archd_dtrust_addr();
    }
 public function getntwrkDTrustFeeEstimate(
        $amnts, $fromaddrs, $toaddrs)
    {
        return $this->ytrf->get_dtrust_ntwrk_fee_estimate([
                        'amnts'        => $amnts,
                        'from_addr' => $fromaddrs,
                        'to_addr'   => $toaddrs,
                    ]);
    }
  public function sweepFromaddrs($fromaddrs, $toaddrs, $privateKey)
    {
        return $this->ytrf->sweep_from_addrs(
                                    [
                                        'from_addrs' => $fromaddrs,
                                        'to_addrs'   => $toaddrs,
                                        'private_key'  => $privateKey,
                                    ]
                               );
    }
}
