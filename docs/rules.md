# Default Validation Rules

Validation rules are based on the proposal for phpdoc tags [PRS-19](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md).

There are 3 issue levels:

* `error` = issue MUST be fixed
* `warning` = issue SHOULD be fixed
* `notice` = issue MAY be fixed

See [configuration option rules](configuration.md#rules) on how to add your own rules or change the default set. 

## Errors

### DocBlockApiNotInternalRule

```yaml
DocBlockApiNotInternalRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockApiNotInternalRule
    issueLevel: error
```

### DocBlockApiVisibilityPublicRule

```yaml
DocBlockApiVisibilityPublicRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockApiVisibilityPublicRule
    issueLevel: error
```

### DocBlockAuthorNameMissingRule

```yaml
DocBlockAuthorNameMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockAuthorNameMissingRule
    issueLevel: error
```

### DocBlockInvalidTagsRule

```yaml
DocBlockInvalidTagsRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockInvalidTagRule
    issueLevel: error
```

### DocBlockLinkUrlRule

```yaml
DocBlockLinkUrlRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockLinkUrlRule
    issueLevel: error
    match: { isInternal: false, isPublic: true }
```

### DocBlockMethodAllowedRule

```yaml
DocBlockMethodAllowedRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMethodAllowedRule
    issueLevel: error
```

### DocBlockParamAllowedRule

```yaml
DocBlockParamAllowedRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockParamAllowedRule
    issueLevel: error
```

### DocBlockParamNameOnlyOnceRule

```yaml
DocBlockParamNameOnlyOnceRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockParamNameOnlyOnceRule
    issueLevel: error
```

### DocBlockParamTypeRule

```yaml
DocBlockParamTypeRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockParamTypeRule
    issueLevel: error
```

### DocBlockParamUnknownRule

```yaml
DocBlockParamUnknownRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockParamUnknownRule
    issueLevel: error
```

### DocBlockReturnAllowedRule

```yaml
DocBlockReturnAllowedRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockReturnAllowedRule
    issueLevel: error
```

### DocBlockReturnOnlyOnceRule

```yaml
DocBlockReturnOnlyOnceRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockReturnOnlyOnceRule
    issueLevel: error
```

## Warnings

### DocBlockApiNoDescriptionRule

```yaml
DocBlockApiNoDescriptionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockApiNoDescriptionRule
    issueLevel: warning
```

### DocBlockCopyrightYearRule

```yaml
DocBlockCopyrightYearRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockCopyrightYearRule
    issueLevel: warning
```

### DocBlockDeprecatedCorrespondingSeeRule

```yaml
DocBlockDeprecatedCorrespondingSeeRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedCorrespondingSeeRule
    issueLevel: warning
```

### DocBlockDeprecatedVersionRule

```yaml
DocBlockDeprecatedVersionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedVersionRule
    issueLevel: warning
```

### DocBlockSeeDescriptionRule

```yaml
DocBlockSeeDescriptionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockSeeDescriptionRule
    issueLevel: warning
```

### DocBlockSinceVersionRule

```yaml
DocBlockSinceVersionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockSinceVersionRule
    issueLevel: warning
```

### PublicClassDocBlockMissingRule

```yaml
PublicClassDocBlockMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule
    issueLevel: warning
    match: { getElementType: Class, isInternal: false }
```

### PublicDocBlockSummaryMissingRule

```yaml
PublicDocBlockSummaryMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockSummaryMissingRule
    issueLevel: warning
    match: { isInternal: false, isPublic: true }
```

### PublicInterfaceDocBlockMissingRule

```yaml
PublicInterfaceDocBlockMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule
    issueLevel: warning
    match: { getElementType: Interface, isInternal: false }
```

### PublicMethodDocBlockMissingRule

```yaml
PublicMethodDocBlockMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule
    issueLevel: warning
    match: { getElementType: Method, isPublic: true, isInternal: false }
```

### PublicTraitDocBlockMissingRule

```yaml
PublicTraitDocBlockMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule
    issueLevel: warning
    match: { getElementType: Trait, isInternal: false }
```

### TypeMissingRule

```yaml
TypeMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\TypeMissingRule
    issueLevel: warning
```

## Notices

### DocBlockAuthorEmailRule

```yaml
DocBlockAuthorEmailRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockAuthorEmailRule
    issueLevel: notice
```

### DocBlockDeprecatedDescriptionRule

```yaml
DocBlockDeprecatedDescriptionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockDeprecatedDescriptionRule
    issueLevel: notice
```

### DocBlockLinkDescriptionRule

```yaml
DocBlockLinkDescriptionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockLinkDescriptionRule
    issueLevel: notice
    match: { isInternal: false, isPublic: true }
```

### DocBlockSinceDescriptionRule

```yaml
DocBlockSinceDescriptionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockSinceDescriptionRule
    issueLevel: notice
```

### FileDocBlockCopyrightMissingRule

```yaml
FileDocBlockCopyrightMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockCopyrightMissingRule
    issueLevel: notice
    match: { getElementType: File, isInternal: false }
```

### FileDocBlockLicenseMissingRule

```yaml
FileDocBlockLicenseMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockLicenseMissingRule
    issueLevel: notice
    match: { getElementType: File, isInternal: false }
```

### PublicDocBlockDescriptionMissingRule

```yaml
PublicDocBlockDescriptionMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockDescriptionMissingRule
    issueLevel: notice
    match: { isInternal: false, isPublic: true }
```

### PublicDocBlockParamDescriptionRule

```yaml
PublicDocBlockParamDescriptionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockParamDescriptionRule
    issueLevel: notice
    match: { isInternal: false, isPublic: true }
```

### PublicDocBlockReturnDescriptionRule

```yaml
PublicDocBlockReturnDescriptionRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockReturnDescriptionRule
    issueLevel: notice
    match: { isPublic: true, isInternal: false }
```

### PublicFileDocBlockMissingRule

```yaml
PublicFileDocBlockMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule
    issueLevel: notice
    match: { getElementType: File, isInternal: false }
```

### PublicFunctionDocBlockMissingRule

```yaml
PublicFunctionDocBlockMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule
    issueLevel: notice
    match: { getElementType: Function, isInternal: false }
```

### PublicPropertyDocBlockMissingRule

```yaml
PublicPropertyDocBlockMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockMissingRule
    issueLevel: notice
    match: { getElementType: Property, isPublic: true, isInternal: false }
```

## Ignored

### FileDocBlockLinkMissingRule

```yaml
FileDocBlockLinkMissingRule:
    class: Klitsche\Dog\Analyzer\Rules\DocBlockLinkMissingRule
    issueLevel: ignore
    match: { getElementType: File, isInternal: false }
```
