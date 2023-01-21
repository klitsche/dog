# Printers

A printer usually processes the collected project data and outputs it in a specific format.

Dog comes with a bundled markdown Printer, but you may use a customized printer instead to output other formats
like `html` or `pdf`.

## How do they work?

A Printer class must implement `Klitsche\Dog\PrinterInterface`. It is then utilized in a 2 phase process:

### 1. Create

After dog did build the `Project` the Printer is initialized by
calling `create(ConfigInterface $config, EventDispatcherInterface $dispatcher): self`.

You can access the custom printer configuration via the `ConfigInterface::getPrinterConfig` interface.
The dispatcher can be used to emit progress or errors which will be handled by the dog app.

!!! Tip

    Use the provided traits `Klitsche\Dog\Events\ErrorEmitterTrait`, `Klitsche\Dog\Events\EventDispatcherAwareTrait, 
    and `Klitsche\Dog\Events\ProgressEmitterTrait` to simplify emitting pogress or errors.

### 2. Print

After dog has built the `Project` the printer then will be called via `print(ProjectInterface $project): void`
and the printer can do its job.

## 3rd party Printer

- [csoellinger/dog-html-printer](https://github.com/CSoellinger/dog-html-printer) (HTML)

!!! Hint

    Please open up a PR to list your 3rd party printer here.

