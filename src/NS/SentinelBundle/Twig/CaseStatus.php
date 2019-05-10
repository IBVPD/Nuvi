<?php

namespace NS\SentinelBundle\Twig;

use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Entity\BaseSiteLabInterface;
use NS\SentinelBundle\Validators\Cache\CachedValidations;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CaseStatus extends AbstractExtension
{
    /** @var CachedValidations */
    private $cachedValidator;

    /** @var CamelCaseToSnakeCaseNameConverter */
    private $namer;

    public function __construct(CachedValidations $cachedValidator, CamelCaseToSnakeCaseNameConverter $namer)
    {
        $this->cachedValidator = $cachedValidator;
        $this->namer           = $namer;
    }

    public function getFunctions(): array
    {
        $isSafe = ['is_safe' => ['html']];

        return [
            new TwigFunction('case_field_error', [$this, 'getCaseFieldIssue'], $isSafe),
            new TwigFunction('case_lab_field_error', [$this, 'getCaseLabFieldIssue'], $isSafe),
            new TwigFunction('case_external_field_error', [$this, 'getExternalLabFieldIssue'], $isSafe),
            new TwigFunction('case_label', [$this, 'getLabel'], $isSafe),
            new TwigFunction('case_lab_label', [$this, 'getLabLabel'], $isSafe),
            new TwigFunction('case_rrl_label', [$this, 'getRRLLabel'], $isSafe),
            new TwigFunction('case_nl_label', [$this, 'getNLLabel'], $isSafe),
        ];
    }

    /**
     * @param BaseCase $obj
     * @param          $message
     *
     * @return null|string
     */
    public function getNLLabel(BaseCase $obj, $message): ?string
    {
        if ($obj->getSentToNationalLab() || $obj->hasNationalLab()) {
            if ($obj->getSentToNationalLab() && $obj->hasNationalLab()) {
                $popover = $this->getPopover($obj->getId(), $obj->getNationalLab(), $obj->getRegion()->getCode());
                $class   = empty($popover) ? 'label-success fa fa-check' : 'label-warning fa fa-exclamation';
            } else {
                $popover = '<ul><li><strong>Missing</strong>: No National Lab Record</li></ul>';
                $class   = 'label-danger fa fa-exclamation-sign';
            }

            return '<a href="javascript:;" data-toggle="popover" data-html="true" data-content="' . $popover . '" class="label label-sm ' . $class . '"> ' . $message . '</a>';
        }

        return null;
    }

    /**
     * @param BaseCase $obj
     * @param          $message
     *
     * @return null|string
     */
    public function getRRLLabel(BaseCase $obj, $message): ?string
    {
        if ($obj->getSentToReferenceLab() || $obj->hasReferenceLab()) {
            if ($obj->getSentToReferenceLab() && $obj->hasReferenceLab()) {
                $popover = $this->getPopover($obj->getId(), $obj->getReferenceLab(), $obj->getRegion()->getCode());
                $class   = empty($popover) ? 'label-success fa fa-check' : 'label-warning fa fa-exclamation';
            } else {
                $popover = '<ul><li><strong>Missing</strong>: No Reference Lab Result</li></ul>';
                $class   = 'label-danger fa fa-exclamation-sign';
            }

            return '<a href="javascript:;" data-toggle="popover" data-html="true" data-content="' . $popover . '" class="label label-sm ' . $class . '"> ' . $message . '</a>';
        }

        return null;
    }

    /**
     * @param BaseCase $obj
     * @param          $message
     *
     * @return string
     */
    public function getLabLabel(BaseCase $obj, $message): string
    {
        $issues = null;
        if ($obj->getSiteLab()) {
            $popover = $this->getPopover($obj->getId(), $obj->getSiteLab(), $obj->getRegion()->getCode());
            $class   = empty($popover) ? 'label-success fa fa-check' : 'label-warning fa fa-exclamation';
        } else {
            $popover = '<ul><li><strong>Missing</strong>: No Site Lab Result</li></ul>';
            $class   = 'label-danger fa fa-exclamation';
        }

        return '<a href="javascript:;" data-toggle="popover" data-html="true" data-content="' . $popover . '" class="label label-sm ' . $class . '"> ' . $message . '</a>';
    }

    /**
     * @param BaseCase $obj
     * @param string   $message
     *
     * @return string
     */
    public function getLabel(BaseCase $obj, string $message): string
    {
        $popover = $this->getPopover($obj->getId(), $obj, $obj->getRegion()->getCode());

        $class = empty($popover) ? 'label-success fa fa-check' : 'label-warning fa fa-exclamation';
        if (!$obj->getSiteLab()) {
            $popover = '<ul><li><strong>Missing</strong>: No Site Lab Result</li></ul>';
            $class = 'label-danger fa fa-exclamation';
        }

        return '<a href="javascript:;" data-toggle="popover" data-html="true" data-content="' . $popover . '" class="label label-sm ' . $class . '"> ' . $message . '</a>';
    }

    private function getPopover(string $key, $obj, string $regionCode): string
    {
        $issues  = $this->cachedValidator->validate($key, $obj, ["$regionCode+Completeness", 'Completeness']);
        $popover = '';
        if (!empty($issues)) {
            $popover = '<ul>';
            foreach ($issues as $property => $issue) {
                $popover .= sprintf('<li><strong>%s</strong>: %s</li>', $property, htmlentities(implode('<br/>', $issue)));
            }
            $popover .= '</ul>';
        }

        return $popover;
    }

    /** @var array|null */
    private $issues;

    public function getCaseFieldIssue(BaseCase $obj, ?string $field = null): ?string
    {
        $errors = $this->getField($obj->getId(), get_class($obj), $field);
        if ($errors === null) {
            return null;
        }

        if ($field === null) {
            return $this->getRemainingErrors($errors);
        }

        return sprintf('<ul class="complete"><li>%s</li></ul>', implode('</li><li>', $errors));
    }

    public function getCaseLabFieldIssue(BaseSiteLabInterface $siteLab, ?string $field = null): ?string
    {
        $errors = $this->getField($siteLab->getCaseFile()->getId(), get_class($siteLab), $field);
        if ($errors === null) {
            return null;
        }

        if ($field === null) {
            return $this->getRemainingErrors($errors);
        }

        return sprintf('<ul class="complete"><li>%s</li></ul>', implode('</li><li>', $errors));
    }

    public function getExternalLabFieldIssue(BaseExternalLab $siteLab, ?string $field = null): ?string
    {
        $errors = $this->getField($siteLab->getCaseFile()->getId(), get_class($siteLab), $field);
        if ($errors === null) {
            return null;
        }

        if ($field === null) {
            return $this->getRemainingErrors($errors);
        }

        return sprintf('<ul class="complete"><li>%s</li></ul>', implode('</li><li>', $errors));
    }

    private function getRemainingErrors(array $errors): string
    {
        $output = '<ul class="complete">';
        foreach ($errors as $key => $values) {
            $output .= sprintf('<li><strong>%s</strong>:<ul><li>%s</li></ul></li>', $key, implode('</li><li>', $values));
        }
        return $output . '</ul>';
    }

    private function getField(string $key, string $subKey, ?string $field): ?array
    {
        if (!isset($this->issues[$key])) {
            $this->issues[$key] = $this->cachedValidator->collect($key);
        }

        if (!isset($this->issues[$key][$subKey])) {
            return null;
        }

        if ($field === null) {
            return $this->issues[$key][$subKey];
        }

        $issue = &$this->issues[$key][$subKey];
        if (isset($issue[$field])) {
            $var = $issue[$field];
            unset($issue[$field]);
            return $var;
        }

        $normalized = $this->namer->normalize($field);
        if (isset($issue[$normalized])) {
            $var = $issue[$normalized];
            unset($issue[$normalized]);
            return $var;
        }


        $denormalized = $this->namer->denormalize($field);
        $var          = $issue[$denormalized] ?? null;
        if ($var !== null) {
            unset($issue[$normalized]);
            return $var;
        }

        $lower = [
            str_replace('_', '', strtolower($field)),
            str_replace('_', '', strtolower($normalized)),
            str_replace('_', '', strtolower($denormalized)),
        ];

        foreach ($issue as $issueKey => $values) {
            $newKey = strtolower(str_replace('_', '', $issueKey));
            if (in_array($newKey, $lower, true)) {
                $vars = $values;
                unset($issue[$issueKey]);
                return $vars;
            }
        }

        return null;
    }
}
