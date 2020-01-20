<?php

namespace Saffron\Book\Block\Sales\Totals;

class Bookdiscount extends \Magento\Framework\View\Element\Template {

	protected $_dataHelper;

	protected $_order;

	protected $_source;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		\Saffron\Book\Helper\Data $dataHelper,
		array $data = []
		) {
		$this -> _dataHelper = $dataHelper;
		parent :: __construct($context, $data);
	}

	public function displayFullSummary() {
		return true;
	}

	public function getSource() {
		return $this -> _source;
	}

	public function getStore() {
		return $this -> _order -> getStore();
	}

	public function getOrder() {
		return $this -> _order;
	}

	public function getLabelProperties() {
		return $this -> getParentBlock() -> getLabelProperties();
	}

	public function getValueProperties() {
		return $this -> getParentBlock() -> getValueProperties();
	}

	public function initTotals() {
		$parent = $this -> getParentBlock();
		$this -> _order = $parent -> getOrder();
		$this -> _source = $parent -> getSource();

		$bookdiscount = new \Magento\Framework\DataObject([
			'code' => 'bookdiscount',
			'strong' => false,
			'value' => $this -> _source -> getBookdiscount(),
			'label' => $this -> _dataHelper -> getFeeLabelBookdiscount(),
			]
			);

		$parent -> addTotal($bookdiscount, 'bookdiscount');

		return $this;
	}
} 